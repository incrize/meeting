package sender

import "github.com/nlopes/slack"

// Client for the slack api
type Client struct {
	SlackAPI *slack.Client
}

// New creates new client
func New(token string) *Client {
	c := &Client{
		SlackAPI: slack.New(token),
	}

	return c
}

// FindUserIDByEmail finds Slack User by email
func (c *Client) FindUserIDByEmail(email string) (string, error) {
	user, err := c.SlackAPI.GetUserByEmail(email)

	if err == nil {
		return user.ID, err
	}

	return "", err
}

// Send sends message
func (c *Client) Send(userID string, message string) error {
	_, _, err := c.SlackAPI.PostMessage(userID, slack.MsgOptionText(message, false))

	return err
}
