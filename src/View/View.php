<?php

namespace Roto\View;

class View {

	protected $source;
	protected $data;

	public function __construct($source, $data = array()) {
		$this->source = $source;
		$this->data = $data;
	}

	public function render(View $subView = null) {
		extract ($this->data);
		include $this->source;
	}
}
