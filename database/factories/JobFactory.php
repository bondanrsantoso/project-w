<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Worker;
use App\Models\Workgroup;
use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    private $workgroupIds, $categoryIds;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->faker = fake("en_US");
        $this->workgroupIds = Workgroup::all(["id"])->pluck("id")->all();
        $this->categoryIds = JobCategory::all(["id"])->pluck("id")->all();

        $startDate = $this->faker->dateTimeThisYear();
        $durationDays = rand(15, 60);
        $endDate = (new DateTime($startDate->format("Y-m-d 23:59:59")))
            ->add(DateInterval::createFromDateString("+{$durationDays} days"));

        return [
            "name" => $this->faker->bs(),
            "description" => $this->faker->realText(255),
            "order" => $this->faker->randomDigit(),
            "budget" => $this->faker->numberBetween(1000, 25000) * 1000,
            "status" => $this->faker->randomElement(
                [
                    Job::STATUS_PENDING,
                    Job::STATUS_CANCELED,
                    Job::STATUS_DONE,
                    Job::STATUS_ON_PROGRESS,
                    Job::STATUS_PAID,
                ]
            ),
            "date_start" => $startDate->format("Y-m-d H:i:s"),
            "date_end" => $endDate->format("Y-m-d H:i:s"),
            "workgroup_id" => $this->faker->randomElement($this->workgroupIds),
            "job_category_id" => $this->faker->randomElement($this->categoryIds),
            "worker_id" => null,
        ];
    }
}
