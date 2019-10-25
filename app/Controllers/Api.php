<?php

namespace App\Controllers;

use App\Models\Link;
use Carbon\Carbon;
use FL\Application;
use Illuminate\Support\Str;

class Api
{
	private $modelLink;

	function __construct()
	{
		$this->modelLink = new Link;

		$data = [];
		$rand = rand(40, 200);
		$rand = 0;

		for ($i=0; $i < $rand ; $i++) {
			$_d = [
				'name' => Str::random(rand(12, 30)),
				'url' => 'http://link.com',
				'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magnam totam quod',
				'created_at' => Carbon::now()->toDateTimeString(),
				'updated_at' => Carbon::now()->toDateTimeString(),
			];

			array_push($data, $_d);
		}

		$db = Application::$instance->db;
		$db->table('links')
			->insert($data);
	}

	public function links()
	{
		$model = $this->modelLink;
		$links = $model->pagination();
		$s = isset($_GET['search'])
				? trim($_GET['search'])
				: null;

		if (!is_null($s) && strlen($s) > 0) {
			$model = $model->getQuery()->where('name', 'LIKE', '%'.$s.'%');
		}

		$model = $model->where('user_id', '=', auth()->user()->id);

		$links = $links->setModel($model);
		return $links->getData();
	}

	public function getLink($id)
	{
		$link = Link::where('id', '=',$id)
				->where('user_id', '=', auth()->user()->id)
				->first();

		return [
			'status' => is_null($link)
					? 0 : 1,
			'link' => $link,
		];
	}

	public function updateLink($id)
	{
		$data = request()->post;
		$link = Link::find($id);

		if (!is_null($link)) {
			$link->update([
				'name' => $data['name'],
				'url' => $data['url'],
				'description' => $data['description'],
			]);

			return [
				'status' => 1,
				'link' => $link,
				'message' => 'Link Updated',
			];
		} else {
			return [
				'status' => 0,
				'link' => $link,
				'message' => 'Link Not Found',
			];
		}
	}

	public function storeLink()
	{
		$data = request()->post;

		$data['user_id'] = auth()->user()->id;

		$link = new Link($data);
		$link->save();

		return [
			'status' => 1,
			'message' => 'Link Stored',
		];
	}

	public function deleteLink($id)
	{
		$link = Link::find($id);

		if (!is_null($link)) {
			$link->delete();
		}

		return [
			'status' => 1,
			'message' => 'Deleted',
		];
	}
}
