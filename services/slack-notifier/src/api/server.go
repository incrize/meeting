package api

import (
	"encoding/json"
	"fmt"
	"net/http"
	"time"

	"github.com/go-chi/chi"
	"github.com/go-chi/chi/middleware"
	"github.com/go-pkgz/lgr"
	"github.com/go-pkgz/rest"
	"github.com/go-pkgz/rest/logger"
	"github.com/incrize/slack_notification_sender/src/sender"
)

// Server provides HTTP API
type Server struct {
	Version string

	sender     *sender.Client
	httpServer *http.Server
}

// Request struct is represents struct of POST query for send message
type Request struct {
	ChanelID string `json:"chanelId"`
	Email    string `json:"email"`
	Message  string `json:"message"`
}

// Run starts http server for API with all routes
func (s *Server) Run(port int, sender *sender.Client) {

	s.sender = sender
	l := logger.New(logger.Log(lgr.Default()), logger.Prefix("[INFO]"))
	router := chi.NewRouter()

	router.Use(middleware.RealIP, rest.Recoverer(lgr.Default()))
	router.Use(middleware.Throttle(1000), middleware.Timeout(60*time.Second))
	router.Use(rest.AppInfo("Slack Notification sender", "incrize", s.Version), rest.Ping)
	router.Use(l.Handler)

	router.Post("/message", s.actionSend)

	s.httpServer = &http.Server{
		Addr:              fmt.Sprintf(":%d", port),
		Handler:           router,
		ReadHeaderTimeout: 5 * time.Second,
		WriteTimeout:      30 * time.Second,
		IdleTimeout:       30 * time.Second,
	}

	err := s.httpServer.ListenAndServe()
	lgr.Printf("[WARN] http server terminated, %s", err)
}

func (s *Server) actionSend(w http.ResponseWriter, r *http.Request) {
	var request Request

	decoder := json.NewDecoder(r.Body)
	err := decoder.Decode(&request)

	if err != nil {
		rest.SendErrorJSON(w, r, lgr.Default(), http.StatusBadRequest, err, "failed to decode response")
		return
	}

	if request.ChanelID == "" && request.Email == "" {
		rest.SendErrorJSON(w, r, lgr.Default(), http.StatusBadRequest, err, "chanelId or email must be specified")
		return
	}

	if request.Message == "" {
		rest.SendErrorJSON(w, r, lgr.Default(), http.StatusBadRequest, err, "message be specified")
		return
	}

	if request.ChanelID != "" {
		err = s.sender.Send(request.ChanelID, request.Message)
	} else if request.Email != "" {
		userID, erruser := s.sender.FindUserIDByEmail(request.Email)

		if erruser != nil {
			rest.SendErrorJSON(w, r, lgr.Default(), http.StatusBadRequest, erruser, "could not find user")
			return
		}

		err = s.sender.Send(userID, request.Message)
	}

	if err != nil {
		rest.SendErrorJSON(w, r, lgr.Default(), http.StatusBadRequest, err, "could not send message")
	}

	w.WriteHeader(http.StatusCreated)
}
