<?php

declare(strict_types=1);

namespace App;

use ICal\ICal;
use it\thecsea\simple_caldav_client\SimpleCalDAVClient;

final class CalDav
{
    private static self $instance;

    private string $url;

    private string $user;

    private string $password;

    private SimpleCalDAVClient $client;

    private array $calendars;

    private function __construct()
    {
        $this->url = config('caldav.url');
        $this->user = config('caldav.user');
        $this->password = config('caldav.password');

        $this->client = new SimpleCalDAVClient;
        $this->client->connect($this->url, $this->user, $this->password);
    }

    public static function make()
    {
        if ( ! isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function calendars(?string $id = null)
    {
        if ( ! isset($this->calendars)) {
            $this->calendars = $this->client->findCalendars();
        }

        return $id === null ? collect($this->calendars)->values() : $this->calendars[$id];
    }

    public function todos(string $calendarId)
    {
        [$protocol, $rest] = explode('://', $this->url, limit: 2);

        $todos = new ICal("{$protocol}://{$this->user}:{$this->password}@{$rest}/{$calendarId}?export")->cal['VTODO'];

        return collect($todos);
    }
}
