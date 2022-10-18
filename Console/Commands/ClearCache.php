<?php

namespace Modules\Appearance\Console\Commands;

use Modules\Appearance\Facades\ThemesManager;

class ClearCache extends AbstractCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'theme:cache:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear themes cache';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Prompt for module's alias name.
     */
    public function handle()
    {
        if (ThemesManager::clearCache()) {
            $this->sectionMessage('Themes Manager', 'Themes cache cleared succefully');
        } else {
            $this->error('An error occured while clearing themes cache');
        }
    }
}
