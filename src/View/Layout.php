<?php

namespace Roto\View;

class Layout extends View {

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function setMany(array $map) {
		foreach ($map as $key => $value) {
			$this->data[$key] = $value;
		}
	}

	public function hasSource() {
		return (bool)$this->source;
	}

	public function setSource($source) {
		$this->source = $source;
	}
}
