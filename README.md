# Pong Scorer
Scores a simple game of pong.
I couldn't find any other existing applications where I can have a separate scoreboard on an idevice and update
scores and team names on another device, so I decided to build one.
# Instructions
1. Put client.php and server.php into the www folder in a PHP-compatible web server.
2. Run the PHP-compatible web server, ensuring that you have set up PHP properly.
3. Open `http://<IP Address>/client.php` on the device that is displaying the scoreboard.
4. Open `http://<IP Address>/server.php` on the device that is scoring.
# Feature List
- Allows server to add and remove points from teams
- Allows server to change team names
# Roadmap and future features
- [x] Show who is currently serving
- [x] Show current round
- [x] Allow multiple scorers
- [ ] Allow manual override of the player that is currently serving
- [ ] Show when a player has won
- [x] Allow multiple games to go at once
- [x] Switch from storing data to data.txt into using PHP Sessions and pairing
- [ ] Allow an "Audit Log" - the reasons points have been taken or added
- [ ] Add text-to-speech commentator announcements