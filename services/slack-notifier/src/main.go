package main

import (
	"fmt"
	"os"

	"github.com/go-pkgz/lgr"
	"github.com/incrize/slack_notification_sender/src/api"
	"github.com/incrize/slack_notification_sender/src/sender"
	"github.com/jessevdk/go-flags"
)

var opts struct {
	Token string `short:"t" long:"token" env:"SNS_API_TOKEN" description:"Slack APP OAuth Access Token" required:"true"`
	Port  int    `short:"p" long:"port" env:"SNS_SERVER_PORT" default:"8080" description:"API Server listen port" required:"true"`

	Dbg bool `long:"dbg" env:"DEBUG" description:"debug mode"`
}

var version = "0.0.1"

func main() {
	fmt.Printf("Slack notification sender %s\n", version)

	if _, err := flags.Parse(&opts); err != nil {
		os.Exit(1)
	}

	initLoger(opts.Dbg)

	client := sender.New(opts.Token)
	server := api.Server{}

	server.Run(opts.Port, client)
}

func initLoger(dbg bool) {
	if dbg {
		lgr.Setup(lgr.Debug, lgr.CallerFile, lgr.Msec, lgr.LevelBraces)
		return
	}

	lgr.Setup(lgr.Msec, lgr.LevelBraces)
}
