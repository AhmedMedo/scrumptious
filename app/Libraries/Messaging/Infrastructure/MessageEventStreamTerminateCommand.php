<?php

namespace App\Libraries\Messaging\Infrastructure;

use App\Libraries\Base\Database\ConnectionService;
use App\Libraries\Messaging\Infrastructure\Entity\EventStreamEntity;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Helper\ProgressBar;

final class MessageEventStreamTerminateCommand extends Command
{
    /** @var string */
    protected $signature = 'event-storage:terminate';

    /** @var string */
    protected $description = 'Removes all old event storage. Run in scheduler.';

    private array $tables = [
        'activation'                => 'user_uuid',
        'customer'                  => 'uuid',
        'user'                      => 'uuid',
        'vehicle'                   => 'uuid',
        'vehicle_maker'             => 'uuid',
        'vehicle_model'             => 'uuid',
        'cancel_reason'             => 'uuid',
        'organization'              => 'uuid',
        'company'                   => 'uuid',
        'page'                      => 'uuid',
        'slider'                    => 'uuid',
        'supplier_business_manager' => 'uuid',
    ];

    private ProgressBar $bar;

    public function handle(EventStreamEntity $db, ConnectionService $connectionService): void
    {
        $connectionService->disableLog();

        $date = Carbon::now()->subDays(2 * 7);
        $collection = Collection::make($this->tables);

        $this->bar = $this->output->createProgressBar($collection->count());
        $this->bar->setFormat("%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s%");

        foreach ($this->tables as $table => $id) {
            $db->newQuery()
                ->join($table, $table . '.' . $id, '=', 'event_storage.event_id')
                ->where('event_storage.created_at', '<', $date)
                ->forceDelete();

            $this->info('Finished domain: ' . $table);
            $this->bar->advance();
        }

        $this->info('Finished');
    }
}
