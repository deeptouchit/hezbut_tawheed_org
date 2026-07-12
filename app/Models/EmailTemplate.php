<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EmailTemplate extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'email_templates';

    protected $fillable = [
        'name',
        'subject',
        'body',
        'type',
        'placeholders',
        'is_active'
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $appends = [
        'status_badge_html'
    ];

    /**
     * Get the body with HTML entities decoded (CRITICAL FOR PREVIEW)
     */
    public function getBodyAttribute($value)
    {
        if (is_null($value)) {
            return '';
        }

        // Decode HTML entities for proper rendering
        return html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Set the body without double encoding
     */
    public function setBodyAttribute($value)
    {
        // Store as is, don't encode
        $this->attributes['body'] = $value;
    }

    /**
     * Get raw body for editing (without decoding)
     */
    public function getRawBodyAttribute()
    {
        return $this->attributes['body'] ?? '';
    }

    /**
     * Get encoded body for JSON responses
     */
    public function getEncodedBodyAttribute()
    {
        return e($this->body);
    }

    /**
     * Scope for active templates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="badge bg-success">সক্রিয়</span>'
            : '<span class="badge bg-danger">নিষ্ক্রিয়</span>';
    }

    /**
     * Get status badge HTML (for appends)
     */
    public function getStatusBadgeHtmlAttribute()
    {
        return $this->getStatusBadgeAttribute();
    }

    /**
     * Render template with placeholders
     */
    public static function renderTemplate($name, $data = [])
    {
        $template = self::where('name', $name)->first();

        if (!$template) {
            return null;
        }

        $body = $template->body; // This will use the accessor

        // Replace placeholders
        foreach ($data as $key => $value) {
            $body = str_replace('{{' . $key . '}}', $value, $body);
            $body = str_replace('{{ ' . $key . ' }}', $value, $body); // With spaces
        }

        // Add year placeholder
        $body = str_replace('{{year}}', date('Y'), $body);
        $body = str_replace('{{ year }}', date('Y'), $body);

        // Add company name placeholder if exists
        $body = str_replace('{{company_name}}', config('app.name'), $body);
        $body = str_replace('{{ company_name }}', config('app.name'), $body);

        return $body;
    }

    /**
     * Render template with placeholders (non-static method)
     */
    public function render($data = [])
    {
        $body = $this->body; // This will use the accessor

        foreach ($data as $key => $value) {
            $body = str_replace('{{' . $key . '}}', $value, $body);
            $body = str_replace('{{ ' . $key . ' }}', $value, $body);
        }

        // Common placeholders
        $commonPlaceholders = [
            'year' => date('Y'),
            'company_name' => config('app.name'),
            'company_email' => config('mail.from.address'),
            'current_date' => date('d/m/Y'),
            'current_time' => date('H:i:s')
        ];

        foreach ($commonPlaceholders as $key => $value) {
            $body = str_replace('{{' . $key . '}}', $value, $body);
            $body = str_replace('{{ ' . $key . ' }}', $value, $body);
        }

        return $body;
    }

    /**
     * Get all available placeholders from template
     */
    public function getPlaceholdersListAttribute()
    {
        preg_match_all('/\{\{\s*([a-zA-Z_][a-zA-Z0-9_]*)\s*\}\}/', $this->body, $matches);

        $placeholders = array_unique($matches[1] ?? []);

        // Add common placeholders
        $commonPlaceholders = ['year', 'company_name', 'company_email', 'current_date', 'current_time'];

        return array_unique(array_merge($placeholders, $commonPlaceholders));
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-extract and save placeholders when saving
        static::saving(function ($model) {
            if ($model->body) {
                preg_match_all('/\{\{\s*([a-zA-Z_][a-zA-Z0-9_]*)\s*\}\}/', $model->body, $matches);
                $placeholders = array_unique($matches[1] ?? []);
                $model->placeholders = !empty($placeholders) ? json_encode($placeholders) : null;
            }
        });
    }

    /**
     * Get placeholders as array
     */
    public function getPlaceholdersArrayAttribute()
    {
        if (empty($this->placeholders)) {
            return [];
        }

        return json_decode($this->placeholders, true) ?? [];
    }

    /**
     * Check if template has specific placeholder
     */
    public function hasPlaceholder($placeholder)
    {
        return in_array($placeholder, $this->placeholders_array);
    }

    /**
     * Duplicate template
     */
    public function duplicate()
    {
        $newTemplate = $this->replicate();
        $newTemplate->name = $this->name . ' (Copy)';
        $newTemplate->is_active = false;
        $newTemplate->save();

        return $newTemplate;
    }
}
