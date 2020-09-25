<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RefactorReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = $this->getCurrentData();

        Schema::dropIfExists('reviews');

        Schema::create("reviews", function (Blueprint $table) {
            $table->id();

            $table->text("description")
                ->nullable()
                ->comment("Текст отзыва");

            $table->unsignedBigInteger("user_id")
                ->nullable()
                ->comment("Пользователь");

            $table->string("from")
                ->nullable()
                ->comment("От кого");

            $table->unsignedBigInteger("review_id")
                ->nullable()
                ->comment("Отзыв");

            $table->dateTime("moderated_at")
                ->nullable()
                ->comment("Статус публикации");

            $table->dateTime("registered_at")
                ->nullable()
                ->comment("Отображаемая дата отзыва");

            $table->timestamps();
        });

        if ($data) {
            foreach ($data as $item) {
                DB::table("reviews")->insert([
                    "id" => $item->id,
                    "description" => $item->description,
                    "user_id" => $item->user_id,
                    "from" => $item->from,
                    "review_id" => $item->review_id,
                    "moderated_at" => $item->moderated ? $item->updated_at : null,
                    "registered_at" => $item->created_at,
                    "created_at" => $item->created_at,
                    "updated_at" => $item->updated_at,
                ]);
            }
        }
    }

    protected function getCurrentData()
    {
        if (class_exists(\App\Review::class)) {
            return DB::table("reviews")->orderBy("id")->get();
        }
        else {
            return false;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
