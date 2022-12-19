<?php

namespace Database\Seeders;

use App\Models\Installment;
use App\Models\Reduction;
use Illuminate\Database\Seeder;

class ReductionsSeeder extends Seeder
{

	public static function getDefaultReductions()
	{
		$defaultReductions = [
			'id' => 11111,
			'name' => 'None',
			'amount' => 0,
			'description' => 'the default reductions',
			'is_percentage' => false,
			'created_at' => now(),
		];

		return $defaultReductions;
	}


	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$defaultReductions = new Reduction(static::getDefaultReductions());
		$defaultReductions->save();
	}
}
