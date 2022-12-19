<?php

namespace Database\Seeders;

use App\Models\Installment;
use Illuminate\Database\Seeder;

class InstallmentsSeeder extends Seeder
{

	public static function getDefaultInstallments()
	{
		$defaultInstallment = [
			'id' => 11111,
			'name' => 'None',
			'divisions' => [100],
			'description' => 'the default installment',

			'created_at' => now(),
		];

		return $defaultInstallment;
	}


	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$defaultInstallment = new Installment(static::getDefaultInstallments());
		$defaultInstallment->save();
	}
}
