<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'session_id',
        'fee_structure_id',
        'assigned_amount',
        'paid_amount',
        'discount_amount',
        'payment_status',
        'due_date',
        'paid_date',
        'remarks'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'assigned_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    /**
     * Get the student for this fee.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the session for this fee.
     */
    public function session()
    {
        return $this->belongsTo(SchoolSession::class, 'session_id');
    }

    /**
     * Get the fee structure for this fee.
     */
    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    /**
     * Get the remaining amount to be paid.
     */
    public function getRemainingAmountAttribute()
    {
        return $this->assigned_amount - $this->paid_amount - $this->discount_amount;
    }

    /**
     * Check if the fee is fully paid.
     */
    public function getIsFullyPaidAttribute()
    {
        return $this->remaining_amount <= 0;
    }

    /**
     * Check if the fee is overdue.
     */
    public function getIsOverdueAttribute()
    {
        return $this->due_date && $this->due_date < now() && !$this->is_fully_paid;
    }

    /**
     * Scope a query to filter by student.
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope a query to filter by session.
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope a query to filter by payment status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Scope a query to get pending fees.
     */
    public function scopePending($query)
    {
        return $query->whereIn('payment_status', ['pending', 'partial']);
    }

    /**
     * Scope a query to get overdue fees.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereIn('payment_status', ['pending', 'partial']);
    }
}
