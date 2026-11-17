<?php

namespace App\Models\Concerns;

use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;

/**
 * Dipakai di model User (use SyncsChatSnapshot; di dalam class).
 *
 * - Saat customer (role 2) dihapus   : simpan nama + email-nya ke chat_customer_snapshots.
 * - Saat user baru dibuat (email sama): chat lama dipindah ke id baru, nama customer muncul lagi.
 *
 * Catatan: hanya jalan lewat Eloquent ($user->delete(), User::create()), bukan DB::table()->delete().
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait SyncsChatSnapshot
{
    public static function bootSyncsChatSnapshot(): void
    {
        static::deleting(function ($u) {
            if ((string) ($u->role ?? '') !== '2') {
                return;
            }

            DB::table('chat_customer_snapshots')->updateOrInsert(
                ['conv_id' => $u->id],
                [
                    'nama'       => $u->nama ?? null,
                    'email'      => $u->email ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        });

        static::created(function ($u) {
            if (empty($u->email)) {
                return;
            }

            $snaps = DB::table('chat_customer_snapshots')->where('email', $u->email)->get();

            foreach ($snaps as $s) {
                DB::transaction(function () use ($s, $u) {
                    // pindahkan semua pesan ke id baru
                    ChatMessage::where('customer_id', $s->conv_id)
                        ->update(['customer_id' => $u->id]);

                    // status sematan/arsip/blokir ikut pindah
                    $exists = DB::table('chat_conversation_states')->where('conv_id', $u->id)->exists();
                    $q      = DB::table('chat_conversation_states')->where('conv_id', $s->conv_id);
                    $exists ? $q->delete() : $q->update(['conv_id' => $u->id]);

                    DB::table('chat_customer_snapshots')->where('conv_id', $s->conv_id)->delete();
                });
            }
        });
    }
}