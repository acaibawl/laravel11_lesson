<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->orderBy('id')
            ->cursor();

        $download = function () use ($users) {
            $fp = fopen('php://output', 'w');
            fputcsv($fp, ['名前', 'メールアドレス']);

            foreach ($users as $user) {
                $data = [];
                $data[] = $user->name;
                $data[] = $user->email;

                fputcsv($fp, $data);
            }

            fclose($fp);
        };

        return response()->streamDownload(
            $download,
            'users.csv',
            ['Content-Type' => 'text/csv']
        );
    }
}
