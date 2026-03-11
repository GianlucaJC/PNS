<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class utenti extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

	protected $table="utenti";
	protected $connection = 'db_user';	
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userid',
        'passkey',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'passkey',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'passkey' => 'hashed',
    ];

	
    public function getAuthPassword()
    {
      return bcrypt($this->passkey);
    }
    
    /**
     * Verifica le credenziali tramite API centralizzata.
     *
     * @param string $testUser
     * @param string $testPass
     * @return array
     */
    public static function verifica($testUser, $testPass)
    {
        // Rilevamento ambiente
        if (in_array(request()->ip(), ['127.0.0.1', '::1'])) {
            $apiUrl = 'https://localhost:8012/api_user_liof/api_login.php';
        } else {
            $apiUrl = 'http://liojls02.ad.liofilchem.net:8012/api_user_liof/api_login.php';
        }

        $apiSecret = "StringaSegretaMoltoLunga123!";

        try {
            $response = Http::withOptions([
                'verify' => false, // Disabilita verifica SSL
            ])->post($apiUrl, [
                'api_token' => $apiSecret,
                'username'  => $testUser,
                'password'  => $testPass,
                'app_name'  => 'SOS'
            ]);
        } catch (\Exception $e) {
            Log::error('API Login fallito - Errore di connessione', [
                'username' => $testUser,
                'api_url' => $apiUrl,
                'exception' => $e->getMessage()
            ]);
            return ['header' => ['login' => "KO", 'error' => "ERRORE CONNESSIONE: " . $e->getMessage()]];
        }

        $rows = array();

        if ($response->successful()) {
            $json = $response->json();
            
            if (($json['success'] ?? false) === true) {
                $rows['header']['login'] = "OK";
                $rows['operatore'] = $json['operatore'];
            } else {
                $rows['header']['login'] = "KO";
                $rows['header']['error'] = $json['message'] ?? 'Errore sconosciuto';
                Log::warning('API Login fallito - Credenziali non valide o altro errore API', [
                    'username' => $testUser,
                    'api_url' => $apiUrl,
                    'response_json' => $json
                ]);
            }
        } else {
            $rows['header']['login'] = "KO";
            $rows['header']['error'] = "Errore Server: " . $response->status();
            Log::error('API Login fallito - Risposta non successful', [
                'username' => $testUser,
                'api_url' => $apiUrl,
                'status_code' => $response->status(),
                'response_body' => $response->body()
            ]);
        }

        return $rows;
    }    
	

}
