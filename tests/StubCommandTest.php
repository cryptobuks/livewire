<?php

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class StubCommandTest extends TestCase
{
    /** @test */
    public function default_view_stub_is_created()
    {
        Artisan::call('livewire:stubs');

        $this->assertTrue(File::exists($this->stubsPath('livewire.stub')));
        $this->assertTrue(File::exists($this->stubsPath('livewire.inline.stub')));
        $this->assertTrue(File::exists($this->stubsPath('livewire.view.stub')));
    }

    /** @test */
    public function component_is_created_with_view_and_class_custom_default_stubs()
    {
        Artisan::call('livewire:stubs');
        File::append($this->stubsPath('livewire.stub'), '// comment default');
        File::append($this->stubsPath('livewire.inline.stub'), '// comment inline default');
        File::put($this->stubsPath('livewire.view.stub'), '<div>Default Test</div>');
        Artisan::call('make:livewire', ['name' => 'foo']);
        Artisan::call('make:livewire', ['name' => 'bar', '--inline' => true]);

        $this->assertTrue(File::exists($this->livewireClassesPath('Foo.php')));
        $this->assertStringContainsString('// comment default', File::get($this->livewireClassesPath('Foo.php')));
        $this->assertTrue(File::exists($this->livewireClassesPath('Bar.php')));
        $this->assertStringContainsString('// comment inline default', File::get($this->livewireClassesPath('Bar.php')));
        $this->assertTrue(File::exists($this->livewireViewsPath('foo.blade.php')));
        $this->assertStringContainsString('Default Test', File::get($this->livewireViewsPath('foo.blade.php')));
    }

    protected function stubsPath($path = '')
    {
        return base_path('stubs'.($path ? '/'.$path : ''));
    }
}
