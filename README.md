# MoodleBox

A project to build a Moodle server and Wi-Fi router on a Raspberry Pi 3.

The project includes a local plugin for Moodle 3.0 or later, provided to help the administrator of the MoodleBox to monitor some hardware settings and allow restart and shutdown of the MoodleBox.

The documentation is included in the plugin `doc` folder, as a LaTeX document (in french; sorry, no english version as of now, pull request highly desirable).

## Availability

The code is available at [https://github.com/martignoni/moodlebox](https://github.com/martignoni/moodlebox).

An [prepared disk image](https://moodle.org/mod/url/view.php?id=8269) for your Raspberry Pi 3 is [available](https://moodle.org/mod/url/view.php?id=8269).

SHA1 fingerprint of the compressed disk image (moodlebox.img.gz): 44c699a91c39c204dfe4ee4944f54627cd76c151

### Release notes

* 2016-06-26, version 1.0b (beta): added two folder as RAM disks, for better performance
* 2016-06-19, version 1.0a2 (alpha): reorganisation of project
* 2016-06-16, version 1.0a1 (alpha): first version

## Building and installation

To build a MoodleBox from scratch, you need a Raspberri Pi 3 (Wi-Fi!) and follow the [instructions given in the documentation](https://github.com/martignoni/moodlebox/blob/master/doc/Moodlebox.pdf) (in french).

The local plugin needs to be installed in the Moodle tree of the MoodleBox, in the _local_ folder. Once installed, an new option _MoodleBox administration_ will be available in Moodle, under _Site administration > Server_ in the _Administration_ block.

## Features

* Wi-Fi access point. SSID: _MoodleBox_; password: _moodlebox_.
* GUI to restart and shutdown the MoodleBox.
* Moodle 3.1.x LMS reachable via Wi-Fi (or ethernet, see below), URL: [http://moodlebox.local/](http://moodlebox.local/); standard configuration of Moodle with no customisation. An admin account for the Moodle, username: _admin_, password: _Moodlebox4$_. The Moodle server is configured to accept the clients from the Moodle [official mobile app](https://download.moodle.org/mobile/). The maximal size of uploaded files is set to 50Mb. The cron is launched every 3 minutes.
* When a USB key is inserted in the MoodleBox, all the files on it are available for the admins and teachers of the Moodle server, via a _File system_ repository.
* Option to upload files on the MoodleBox via SFTP (username: _moodlebox_, password: _Moodlebox4$_); these files are available for the admins and teachers of the Moodle server, via a _File system_ repository.
* Internet access: when the MoodleBox is connected via ethernet to a network connected to Internet, the MoodleBox acts as a router (IP forwarding) and the Wi-Fi clients have access to Internet.
* [PhpMyAdmin](http://moodlebox.local/phpmyadmin) is installed with an admin account; username: _root_, password: _Moodlebox4$_.

## Usage of the MoodleBox

See the [user manual](https://moodle.org/mod/book/view.php?id=8265), in french.

## Thanks

* To Daniel Méthot, for the [idea of a MoodleBox](https://moodle.org/mod/forum/discuss.php?d=278493)
* To Christian Westphal, for the [first POC](https://moodle.org/mod/forum/discuss.php?d=331170) of a MoodleBox
* To the [Raspberry Pi Foundation](https://www.raspberrypi.org/), for a splendid small computer
* To [Martin Dougiamas](https://en.wikipedia.org/wiki/Martin_Dougiamas), for giving us Moodle, and to the [Moodle community](https://moodle.org/)

## License

Copyright © 2016 onwards, Nicolas Martignoni <nicolas@martignoni.net>

* All the source code is licensed under GPL 3 or any later version
* The documentation is licensed under Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International.

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version. This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.


