<?php
    namespace App\Repositories\Access;

    use App\Models\Access\User;
    use App\Repositories\BaseRepository;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;

    class MyProfileRepository extends BaseRepository {
        const MODEL = User::class;

        public function getMyDetails($userId) {
            return User::find($userId);
        }

        public function update(array $input)
        {
            $user = Auth::user();
            DB::transaction(function () use ($input, $user) {
                $user->update([
                    'phone' => $input['phone'],
                    'first_name' => $input['first_name'],
                    'middle_name' => $input['middle_name'],
                    'last_name' => $input['last_name'],
                ]);
            });
            return $user;
        }

        public function update_password(array $input) {
            $user = Auth::user();
            $user->update([
                'password' => $input['password'],
                'is_password_updated' => true,
            ]);
            return $user;
        }
    }
