# What is that?

Backend which sends notification via Facebook Messenger on Android phone is disconnected from WiFi

# IFTTT setup
If _something happens_
Then _maker_ applet involved with following settings:
* Url `https://MY_APPLICATION.herokuapp.com/leave/?SSID={{SSID}}&OccurredAt={{OccurredAt}}&ConnectedToOrDisconnectedFrom={{ConnectedToOrDisconnectedFrom}}`
* Method `GET`
* Content-Type `text/plain`

# Facebook setup
TODO

# Deployment
    
    # do it once
    $ heroku create
    
    # set env vars
    $ heroku config:set FB_ACCESS_TOKEN=xxx
    $ heroku config:set FB_ID=xxx
    $ heroku config:set FB_VERIFY_TOKEN=xxx
    $ heroku config:set MAKER_KEY=xxx
    $ heroku config:set TZ=timezone
    
    # actual deployment after new commits 
    $ git push heroku master
