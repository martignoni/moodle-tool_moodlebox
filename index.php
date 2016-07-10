<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Main file
 *
 * @package    local
 * @subpackage moodlebox
 * @copyright  2016 Nicolas Martignoni
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$plugin = new stdClass();

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/formslib.php');
require_once(dirname(__FILE__).'/version.php');

require_login();
require_capability('moodle/site:config', context_system::instance());

admin_externalpage_setup('local_moodlebox', '', null);

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$strheading = get_string('pluginname', 'local_moodlebox');
$PAGE->set_title($strheading);
$PAGE->set_heading($strheading);

exec('uname -srm', $kernelversion);
exec('lsb_release -d | cut -d\':\' -f2', $raspbianversion);
$cpuload = sys_getloadavg();
exec('cat /var/lib/misc/dnsmasq.leases', $leases);
$dhcpclientnumber = count($leases);
exec('awk \'{print $1/1000" °C"}\' /sys/class/thermal/thermal_zone0/temp', $cputemperature);
exec('awk \'{print $1/1000" Mhz"}\' /sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq', $cpufrequency);
exec('uptime -p', $uptime);
$moodleboxversion = $plugin->release . ' (' . $plugin->version . ')';
exec('cat /proc/sys/net/ipv4/ip_forward', $ipforward);
$ipforward = $ipforward[0];

class ipforwardtoggle_form extends moodleform {
  public function definition() {
    //global $CFG;
    $mform = $this->_form;
    if ($this->_customdata['ipforward'] <> '1') {
        $buttonaction = 'ipforwardenable';
        $buttonname = get_string('ipforwardenable', 'local_moodlebox');
    } else {
        $buttonaction = 'ipforwarddisable';
        $buttonname = get_string('ipforwarddisable', 'local_moodlebox');
    }
    $mform->addElement('submit', $buttonaction, $buttonname);
  }
}

class restartshutdown_form extends moodleform {
  public function definition() {
    $mform = $this->_form;
    $buttonarray = array();
    $buttonarray[] = & $mform->createElement('submit', 'restartbutton',
                                              get_string('restart', 'local_moodlebox'));
    $buttonarray[] = & $mform->createElement('submit', 'shutdownbutton',
                                              get_string('shutdown', 'local_moodlebox'));
    $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
    $mform->closeHeaderBefore('buttonar');
  }
}

echo $OUTPUT->header();

// System information section
echo $OUTPUT->heading(get_string('systeminfo', 'local_moodlebox'));
echo $OUTPUT->box_start('generalbox');

echo '<table class="admintable environmenttable generaltable" id="moodleboxstatus">';
echo '<thead><tr><th class="header c0" scope="col" width="50%">' . get_string('parameter', 'local_moodlebox') .
      '</th><th class="header c1" scope="col" width="50%">' . get_string('information', 'local_moodlebox') .
      '</th></tr></thead>';
echo '<tbody>';
echo '<tr><th class="cell c0">' . get_string('moodleboxversion', 'local_moodlebox') .
      '</td><td class="cell c1">' . $moodleboxversion . '</td></tr>';
echo '<tr><th class="cell c0">' . get_string('kernelversion', 'local_moodlebox') .
      '</td><td class="cell c1">' . $kernelversion[0] . '</td></tr>';
echo '<tr><th class="cell c0">' . get_string('raspbianversion', 'local_moodlebox') .
      '</td><td class="cell c1">' . $raspbianversion[0] . '</td></tr>';
echo '<tr><th class="cell c0">' . get_string('cpuload', 'local_moodlebox') .
      '</td><td class="cell c1">' . $cpuload[0] . ', ' . $cpuload[1] . ', ' . $cpuload[2] . '</td></tr>';
echo '<tr><th class="cell c0">' . get_string('cputemperature', 'local_moodlebox') .
      '</td><td class="cell c1">' . $cputemperature[0] . '</td></tr>';
echo '<tr><th class="cell c0">' . get_string('cpufrequency', 'local_moodlebox') .
      '</td><td class="cell c1">' . $cpufrequency[0] . '</td></tr>';
echo '<tr><th class="cell c0">' . get_string('uptime', 'local_moodlebox') .
      '</td><td class="cell c1">' . $uptime[0] . '</td></tr>';
echo '<tr><th class="cell c0">' . get_string('dhcpclientnumber', 'local_moodlebox') .
      '</td><td class="cell c1">' . $dhcpclientnumber . '</td></tr>';
if ($dhcpclientnumber > 0) {
  foreach($leases as $row) {
    $item = explode(' ', $row);
    echo '<tr><td class="cell c0" style="padding-left:3em;">' . get_string('clientinfo', 'local_moodlebox') .
          '</td><td class="cell c1">' . $item[2] . ' (' . $item[3] . ')</td></tr>';
  }
}
echo '</tbody>';
echo '</table>';

echo $OUTPUT->box_end();

// IP forwarding section
echo $OUTPUT->heading(get_string('ipforward', 'local_moodlebox'));
echo $OUTPUT->box_start('generalbox');

if ( $ipforward == '1') {
  echo '<p>' . get_string('ipforwardonmessage', 'local_moodlebox') . '</p>';
} else {
  echo '<p>' . get_string('ipforwardoffmessage', 'local_moodlebox') . '</p>';
}

$default_form_data = array('ipforward' => $ipforward);
$ipforwardform = new ipforwardtoggle_form(null, $default_form_data);
$ipforwardform->display();

if ($data = $ipforwardform->get_data()) {
  $file = fopen('.ipforwardtoggle','w');
  fwrite($file, 'Toggle IP forwarding now');
  fclose($file);
  echo '<div class="alert alert-block">Toggle IP forwarding</div>';
}

echo $OUTPUT->box_end();

// Restart-shutdown section
echo $OUTPUT->heading(get_string('restartstop', 'local_moodlebox'));
echo $OUTPUT->box_start('generalbox');

$restartshutdownform = new restartshutdown_form();
$restartshutdownform->display();

if ($data = $restartshutdownform->get_data()) {
// idea from http://stackoverflow.com/questions/5226728/how-to-shutdown-ubuntu-with-exec-php
// adapted for use with incron
  if (!empty($data->restartbutton)) {
    exec('touch .reboot-server');
    echo '<div class="alert alert-block">' . get_string('restartmessage', 'local_moodlebox') . '</div>';
  }
  if (!empty($data->shutdownbutton)) {
    exec('touch .shutdown-server');
    echo '<div class="alert alert-block">' . get_string('shutdownmessage', 'local_moodlebox') . '</div>';
  }
}

echo $OUTPUT->box_end();

echo $OUTPUT->footer();
