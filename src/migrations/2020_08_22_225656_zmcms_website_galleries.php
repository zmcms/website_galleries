<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ZmcmsWebsiteGalleries extends Migration{
	public function up(){
		$tblNamePrefix=(Config('database.prefix')??'');

		$tblName=$tblNamePrefix.'website_galleries';
		Schema::create($tblName, function($table){$table->string('token', 70);});
		Schema::table($tblName, function($table){$table->integer('sort', false, true)->nullable();});	//	Sortowanie kolejności wyświetlania nawigacji
		Schema::table($tblName, function($table){$table->string('access', 70)->default('*');}); // Info, które grupy użytkowników mają dostęp do danej pozycji nawigacji. "*" -> wszyscy mają dostęp, "{'a', 'b', 'd'}" ->grupy a, b oraz d mają dostęp do artykułu
		Schema::table($tblName, function($table){$table->string('frontend_access', 70)->default('*');}); // Info, które grupy użytkowników "z frontu" mają dostęp do danej pozycji nawigacji. "*" -> wszyscy mają dostęp, "{'a', 'b', 'd'}" ->grupy a, b oraz d mają dostęp do artykułu
		Schema::table($tblName, function($table){$table->string('date_from', 10);}); // data od kiedy wyświetla się dana pozycja w nawigacji,
		Schema::table($tblName, function($table){$table->string('date_to', 10)->nullable();}); // data do kiedy wyświetla się dana pozycja w nawigacji, (null - wyświetla się zawsze, chyba, że active jest "0")
		Schema::table($tblName, function($table){$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));});//Imię
		Schema::table($tblName, function($table){$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));});//Imię
		Schema::table($tblName, function($table){$table->index('sort', 'zmcmswgsort1');});
		Schema::table($tblName, function($table){$table->primary('token', 'zmcmswgkey1');});

		$tblName=$tblNamePrefix.'website_galleries_names';
		Schema::create($tblName, function($table){$table->string('token', 70);});
		Schema::table($tblName, function($table){$table->string('langs_id', 5);});// kod języka, np. pl, en itp
		Schema::table($tblName, function($table){$table->string('title', 150);});// Tytuł galerii
		Schema::table($tblName, function($table){$table->string('slug', 150);});// Alug
		Schema::table($tblName, function($table){$table->text('intro')->nullable();});// Wstęp, ops galerii, np umieszczony przed serią zdjęć
		Schema::table($tblName, function($table){$table->string('meta_keywords', 150)->nullable();});// Słowa kluczowe dla treści
		Schema::table($tblName, function($table){$table->string('meta_description', 150)->nullable();});// Opis w sekcji META
		Schema::table($tblName, function($table){$table->string('og_title', 150)->nullable();});// Parametry dla OpenGraph
		Schema::table($tblName, function($table){$table->string('og_type', 150)->nullable();});// Parametry dla OpenGraph
		Schema::table($tblName, function($table){$table->string('og_url', 150)->nullable();});// Parametry dla OpenGraph
		Schema::table($tblName, function($table){$table->string('og_image', 150)->nullable();});// Parametry dla OpenGraph
		Schema::table($tblName, function($table){$table->string('og_description', 150)->nullable();});// Parametry dla OpenGraph
		Schema::table($tblName, function($table){$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));});//Imię
		Schema::table($tblName, function($table){$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));});//Imię
		Schema::table($tblName, function($table){$table->index(['token']);});
		Schema::table($tblName, function($table){$table->primary(['token', 'langs_id'], 'zmcmswgkey3');}); // Link w ramach języka musi być niepowtarzalny
		Schema::table($tblName, function($table) use ($tblNamePrefix){$table->foreign('token')->references('token')->on($tblNamePrefix.'website_galleries')->onUpdate('cascade')->onDelete('cascade');});


		$tblName=$tblNamePrefix.'website_galleries_images';
		Schema::create($tblName, function($table){$table->string('token', 70);});
		Schema::table($tblName, function($table){$table->string('gallery_token', 70);});
		Schema::table($tblName, function($table){$table->integer('sort', false, true)->nullable();});	//	Sortowanie kolejności wyświetlania nawigacji
		Schema::table($tblName, function($table){$table->string('access', 70)->default('*');}); // Info, które grupy użytkowników mają dostęp do danej pozycji nawigacji. "*" -> wszyscy mają dostęp, "{'a', 'b', 'd'}" ->grupy a, b oraz d mają dostęp do artykułu
		Schema::table($tblName, function($table){$table->string('path', 255);}); // Info, które grupy użytkowników "z frontu" mają dostęp do danej pozycji nawigacji. "*" -> wszyscy mają dostęp, "{'a', 'b', 'd'}" ->grupy a, b oraz d mają dostęp do artykułu
		Schema::table($tblName, function($table){$table->text('images_resized')->nullable();});// Ilustracja kategorii
		Schema::table($tblName, function($table){$table->string('mime', 20);}); //Aktywny - 1, Nieaktywny -0. Aktywny się wyświetla, nieaktywny nie.
		Schema::table($tblName, function($table){$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));});//Imię
		Schema::table($tblName, function($table){$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));});//Imię
		Schema::table($tblName, function($table){$table->index('sort', 'zmcmswgisort2');});
		Schema::table($tblName, function($table){$table->index('gallery_token', 'zmcmswgt');});
		Schema::table($tblName, function($table){$table->primary('token', 'zmcmswgkey2');});
		Schema::table($tblName, function($table) use ($tblNamePrefix){$table->foreign('gallery_token')->references('token')->on($tblNamePrefix.'website_galleries')->onUpdate('cascade')->onDelete('cascade');});

		$tblName=$tblNamePrefix.'website_galleries_images_names';
		Schema::create($tblName, function($table){$table->string('token', 70);});
		Schema::table($tblName, function($table){$table->string('langs_id', 5);});// kod języka, np. pl, en itp
		Schema::table($tblName, function($table){$table->string('title', 150);});// Tytuł galerii
		Schema::table($tblName, function($table){$table->string('slug', 150);});// Alug
		Schema::table($tblName, function($table){$table->string('alt', 150);});// Parametr ALT
		Schema::table($tblName, function($table){$table->text('intro')->nullable();});// Wstęp, ops galerii, np umieszczony przed serią zdjęć
		Schema::table($tblName, function($table){$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));});//Imię
		Schema::table($tblName, function($table){$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));});//Imię
		Schema::table($tblName, function($table){$table->index(['token']);});
		Schema::table($tblName, function($table){$table->primary(['token', 'langs_id'], 'zmcmswgkey4');}); // Link w ramach języka musi być niepowtarzalny
		Schema::table($tblName, function($table) use ($tblNamePrefix){$table->foreign('token')->references('token')->on($tblNamePrefix.'website_galleries_images')->onUpdate('cascade')->onDelete('cascade');});


	}
	public function down(){
	}
}
