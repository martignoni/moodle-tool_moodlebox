#!/bin/bash

# This file is part of Moodle - http://moodle.org/
#
# Moodle is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# Moodle is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
#
#
#  * Script file to handle IP forwarding toggle of Moodlebox
#  *
#  * @package    local
#  * @subpackage moodlebox
#  * @copyright  2016 Nicolas Martignoni
#  * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
#  */

# See http://stackoverflow.com/questions/5226728/how-to-shutdown-ubuntu-with-exec-php
if [ -f /var/www/html/local/moodlebox/.ipforwardtoggle ]; then
  rm -f /var/www/html/local/moodlebox/.ipforwardtoggle
  if [ -f /var/www/html/local/moodlebox/.ipforwardtoggle ]; then
    echo "Can't remove file .ipforwardtoggle"
  else # toggle routing status
    echo "start"
    sysctl -w net.ipv4.ip_forward=$(cat /proc/sys/net/ipv4/ip_forward | awk '{print !$1}')
    systemctl daemon-reload
    echo "end"
  fi
fi