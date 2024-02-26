<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class ReducePostLife extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reduce-post-life';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    $posts = Post::where('permanent', false)->get(); // Obtener todos los posts no permanentes

    foreach ($posts as $post) {
        $post->decrement('life_time', 1); // Disminuir la vida en 1 unidad

        if ($post->life_time <= 0) {
            // Eliminar el post directamente si su vida es 0 o menos
            $post->delete();
        }
    }
}

}
