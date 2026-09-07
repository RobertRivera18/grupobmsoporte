<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cedula',
        'ruta_comprobante',
        'qr_codigo',
        'notification',
        'ruta_firma',
        'estado'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    //Relacion de uno a muchos con post
    public function posts()
    {
        return $this->hasMany(Post::class);
    }


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipos::class, 'equipos_user', 'user_id', 'equipo_id');
    }

    public function cuadrillas()
    {
        return $this->belongsToMany(Cuadrilla::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'usu_id');
    }

    public function ticketsAsignados()
    {
        return $this->hasMany(Ticket::class, 'soporte_asignado');
    }

    public function entregasIndumentaria()
    {
        return $this->hasMany(
            \App\Models\EntregaIndumentaria::class,
            'user_id'
        );
    }

    public function devolucionesIndumentaria()
    {
        return $this->hasMany(
            \App\Models\DevolucionIndumentaria::class,
            'user_id'
        );
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    //Auditorias
    public function auditoriasComoAuditor()
    {
        return $this->hasMany(AuditoriaProceso::class, 'auditor_id');
    }

    public function auditoriasComoResponsable()
    {
        return $this->hasMany(AuditoriaProceso::class, 'responsable_id');
    }

    public function salidas()
    {
        return $this->hasMany(SalidaEquipo::class, 'usuario_id');
    }

    public function aprobaciones()
    {
        return $this->hasMany(SalidaEquipo::class, 'aprobado_por');
    }

    public function solicitudesDesvinculacion()
    {
        return $this->hasMany(SolicitudDesvinculacion::class);
    }


    //Ultimas Relaciones
    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'course_enrollments'
        )->withPivot([
            'enrolled_at',
            'completed_at',
            'progress',
            'status',
        ])->withTimestamps();
    }

    public function courseEnrollments()
    {
        return $this->hasMany(
            CourseEnrollment::class
        );
    }

    public function lessonProgress()
    {
        return $this->hasMany(
            LessonProgress::class
        );
    }

    public function quizAttempts()
    {
        return $this->hasMany(
            QuizAttempt::class
        );
    }

    public function createdCourses()
    {
        return $this->hasMany(
            Course::class,
            'created_by'
        );
    }

    public function createdQuestions()
    {
        return $this->hasMany(
            Question::class,
            'created_by'
        );
    }
}
