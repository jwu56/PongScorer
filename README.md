# Pong Scorer
Scores a simple game of pong.
I couldn't find any other existing applications where I can have a separate scoreboard on an idevice and update
scores and team names on another device, so I decided to build one.
# Instructions
1. Put client.php and server.php into HTDocs on a web server
2. Run apache
3. Open http://<IP Address>/client.php on the device that is displaying the scoreboard.
4. Open http://<IP Address>/server.php on the device that is scoring.
# Feature List
- Allows server to add and remove points from teams
- Allows server to change team names
# Roadmap and future features
- [ ] Show who is currently serving
- [ ] Show current round
- [ ] Allow multiple scorers
- [ ] Allow manual override of the player that is currently serving
- [ ] Show when a player has won
- [ ] Allow configuration of winning point number