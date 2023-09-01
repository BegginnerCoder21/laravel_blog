<?php

namespace Tests\Feature\Auths;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    private $user;
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
    }
    public function test_show_posts_at_user(): void
    {
        $categories = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $categories->id
        ]);

        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertViewHas('posts',function ($collection)  use ($post) {
            return $collection->contains($post);
        });
    }

    public function test_empty_posts_table():void
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertSee('Aucun poste trouvé');
        
    }
    
    public function test_unauthentificated_user_cannot_see_create_button():void
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertDontSee('Creer post');
    }
    public function test_authentificated_user_can_see_create_button():void
    {
        $response = $this->actingAs($this->user)->get('/');
        
        $response->assertStatus(200);
        $response->assertSee('Creer post');
    }
    
    
    
    public function test_unauthentificated_user_cannot_create_a_post():void
    {
        $response = $this->get('/post/create');
        
        $response->assertStatus(302);
        $response->assertRedirect('login');
    }
    
    public function test_authentificated_user_can_create_a_post():void
    {
        
        $response = $this->actingAs($this->user)->get('/post/create');
        $response->assertStatus(200);
        
    }
    
    public function test_authentificated_user_can_see_categories_list_create_page():void
    {
        $categories = Category::factory()->create();
        
        $response = $this->actingAs($this->user)->get('/post/create');
        $response->assertStatus(200);
        $response->assertViewHas('categories',function ($collection)  use ($categories) {
            return $collection->contains($categories);
        });
        
    }
    
    public function test_create_post_successfull():void
    {
        Storage::fake('avatars');
        
        $file = UploadedFile::fake()->image('avatar.jpg');

        $categories = Category::factory()->create();
        $post = [
            'title' => 'super title 1',
            'content' => 'content super title 1',
            'image' => $file,
            'category' => $categories->id
        ];
        $response = $this->actingAs($this->user)->post('/post',$post);
        $response->assertStatus(302);
        $response->assertRedirect('/');
        
        $this->assertDatabaseCount('posts',1);
        $this->assertDatabaseHas('posts',[
            'title' => 'super title 1',
            'content' => 'content super title 1',
            'user_id' => $this->user->id,
            'category_id' => $categories->id,
        ]);

    }

    public function test_fields_form_creating_post_cannot_null():void
    {
        $post = [
            'title' => '',
            'content' => '',
            'image' => '',
            'category' => ''
        ];

        $response = $this->actingAs($this->user)->post('post',$post);
        $response->assertSessionHasErrors(['title','content','image','category']);
    }

    public function test_poster_post_content():void
    {
        $categories = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $categories->id
        ]);

        $response = $this->actingAs($this->user)->get('post/' . $post->id);

        $response->assertStatus(200);

        $response->assertSee($post->title);
        $response->assertSee($post->content);
        $response->assertSee($post->images);
    }

    public function test_fields_form_content_post_value_correct():void
    {
        $this->withoutExceptionHandling();

        $categories = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $categories->id
        ]);
        
        $response = $this->actingAs($this->user)->get('post/'. $post->id .'/edit');
        $response->assertOk();
        $response->assertSee('value="' . $post->title .'"',false);
        $response->assertSee('value="' . $post->content .'"',false);
        $response->assertSee('value="' . $post->category_id .'"',false);

        $response->assertViewHas('post',$post);

    }

    public function test_post_update_value_wrong():void
    {

        // $this->withoutExceptionHandling();
        $categories = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $categories->id
        ]);
        
        $posts = [
            'title' => '',
            'content' => '',
            'category' => ''
        ];

        $response = $this->actingAs($this->user)->put('post/'. $post->id,$posts);
        $response->assertSessionHasErrors(['title','content','category']);
    }

    public function test_post_update_value_correct():void
    {

        
        $categories = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $categories->id
        ]);
        
        $response = $this->actingAs($this->user)->put('post/'. $post->id,[
            'title' => 'super title 1 édité',
            'content' => 'content super title 1 édité',
            'category' => $categories->id
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('post.index'));
        $this->assertDatabaseHas('posts',[
            'title' => 'super title 1 édité',
            'content' => 'content super title 1 édité',
            'user_id' => $this->user->id,
            'category_id' => $categories->id
        ]);
        
        
    }
    
    public function test_destroy_post():void
    {
        $this->withoutExceptionHandling();
        $categories = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $categories->id
        ]);

        $response = $this->actingAs($this->user)->delete('post/' . $post->id);
        $response->assertStatus(302);
        $response->assertRedirect(route('post.index'));
        $this->assertDatabaseCount('posts',0);
        $this->assertDatabaseMissing('posts',$post->toArray());
    }
    
    
    
    private function createUser():User
    {
        return User::factory()->create();
    }

}
