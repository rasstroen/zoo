<?php
class CatalogItem
{
	public $id = null;
	private $data = null;
	private $loaded = false;

	public function getTitle()
	{
		$this->load();
		return $this->data['title'];
	}

	public function getName()
	{
		$this->load();
		return $this->data['name'];
	}

	public function getRelativeUrl()
	{
		$this->load();
		return '/catalog/' . $this->getName();
	}

	public function getAdminUrl()
	{
		$this->load();
		return '/admin/catalog/' . $this->id;
	}

	private function load()
	{
		if ($this->loaded) return true;
	}

	function __construct($id, array $data = null)
	{
		$this->data = $data;
		$this->id = $id;
		if (null !== $data) {
			$this->loaded = true;
		}
	}
}