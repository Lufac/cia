<?php

class OptionsCIA{
	public $hostname = "none";
	public $distro = "none";
	public $gw = "none";
  public $ks_type = "none";
	public $storage = "none";
	public $accel = "none";
	public $accel_type_install = "none";
	public $benchmarks_list = array();
	public $ipmi_list = array();

  function getOptionsMsg(){
		$msg = "#++++++++++++++++++++++++++++++++++++++++++++++++++++++++#\n";
		$msg .= "#### CIA inatallation Kickstart (ch3m)  #####\n";
		$msg .= "#### Distro type: $this->distro\n";
		$msg .= "#### Kickstart type: $this->ks_type\n";
		$msg .= "#### Storage type: $this->storage\n";
		$msg .= "#### Accelerator type: $this->accel\n";
		$msg .= "#### Accelerator install: $this->accel_type_install\n";
		$msg .= "#### IPMI options: ".implode(", ", array_keys($this->ipmi_list))."\n";
		$msg .= "#### Benchmark types: ".implode(", ", array_keys($this->benchmarks_list))."\n";
		$msg .= "#### Hostname: $this->hostname\n"; 
		$msg .= "#### Gateway: $this->gw\n"; 
		$msg .= "#++++++++++++++++++++++++++++++++++++++++++++++++++++++++#\n"; 
		return ($msg);
  }
}
?>
