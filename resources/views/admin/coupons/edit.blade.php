@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Modifier le Coupon</h2>
        </div>
        
        <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="card-body">
                <div class="form-preview">
                    <div class="coupon-preview">
                        <div class="coupon-preview-header">
                            <div class="preview-label">Aperçu du Coupon</div>
                            <div class="coupon-preview-status {{ $coupon->is_active ? 'active' : 'inactive' }}">
                                {{ $coupon->is_active ? 'Actif' : 'Inactif' }}
                            </div>
                        </div>
                        <div class="coupon-preview-body">
                            <div class="coupon-preview-code" id="previewCode">{{ $coupon->code }}</div>
                            <div class="coupon-preview-discount" id="previewDiscount">
                                @if($coupon->discount_amount)
                                    {{ $coupon->discount_amount }}€ de réduction
                                @elseif($coupon->discount_percent)
                                    {{ $coupon->discount_percent }}% de réduction
                                @endif
                            </div>
                            <div class="coupon-preview-description" id="previewDescription">
                                {{ $coupon->description ?: 'Aucune description' }}
                            </div>
                            <div class="coupon-preview-expiry" id="previewExpiry">
                                @if($coupon->expires_at)
                                    Expire le: {{ date('d/m/Y', strtotime($coupon->expires_at)) }}
                                @else
                                    Sans date d'expiration
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="code" class="form-label">Code du coupon <span class="required">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $coupon->code) }}" required
                               oninput="updatePreview()">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Type de réduction <span class="required">*</span></label>
                        <div class="discount-type-toggle">
                            <input type="radio" class="btn-check" name="discount_type" id="percent_type" autocomplete="off"
                                   {{ $coupon->discount_percent ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="percent_type" onclick="toggleDiscountType('percent')">
                                Pourcentage (%)
                            </label>
                            
                            <input type="radio" class="btn-check" name="discount_type" id="amount_type" autocomplete="off"
                                   {{ $coupon->discount_amount ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="amount_type" onclick="toggleDiscountType('amount')">
                                Montant (€)
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group" id="percent_discount_group" style="{{ $coupon->discount_percent ? '' : 'display: none;' }}">
                        <label for="discount_percent" class="form-label">Pourcentage de réduction <span class="required">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('discount_percent') is-invalid @enderror" 
                                   id="discount_percent" name="discount_percent" 
                                   value="{{ old('discount_percent', $coupon->discount_percent) }}" 
                                   min="1" max="100" oninput="updatePreview()">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('discount_percent')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group" id="amount_discount_group" style="{{ $coupon->discount_amount ? '' : 'display: none;' }}">
                        <label for="discount_amount" class="form-label">Montant de réduction <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <input type="number" class="form-control @error('discount_amount') is-invalid @enderror" 
                                   id="discount_amount" name="discount_amount" 
                                   value="{{ old('discount_amount', $coupon->discount_amount) }}" 
                                   min="0.01" step="0.01" oninput="updatePreview()">
                        </div>
                        @error('discount_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3"
                                  oninput="updatePreview()">{{ old('description', $coupon->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="expires_at" class="form-label">Date d'expiration</label>
                        <input type="date" class="form-control @error('expires_at') is-invalid @enderror" 
                               id="expires_at" name="expires_at" 
                               value="{{ old('expires_at', $coupon->expires_at ? date('Y-m-d', strtotime($coupon->expires_at)) : '') }}"
                               oninput="updatePreview()">
                        @error('expires_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Laissez vide pour un coupon sans date d'expiration.</small>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                  {{ $coupon->is_active ? 'checked' : '' }} onchange="updatePreview()">
                            <label class="form-check-label" for="is_active">Coupon actif</label>
                        </div>
                        <small class="form-text text-muted">Décochez pour désactiver ce coupon.</small>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="{{ route('coupons.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .form-preview {
        margin-bottom: 2rem;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 2rem;
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .required {
        color: #ef4444;
    }
    
    .discount-type-toggle {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-outline-primary {
        border: 1px solid #3b82f6;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        background-color: white;
        color: #3b82f6;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-outline-primary:hover,
    .btn-check:checked + .btn-outline-primary {
        background-color: #3b82f6;
        color: white;
    }
    
    .coupon-preview {
        max-width: 500px;
        margin: 0 auto;
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .coupon-preview-header {
        background-color: #f3f4f6;
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .preview-label {
        font-weight: 600;
        color: #6b7280;
    }
    
    .coupon-preview-status {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .coupon-preview-status.active {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .coupon-preview-status.inactive {
        background-color: #fee2e2;
        color: #b91c1c;
    }
    
    .coupon-preview-body {
        padding: 2rem;
        text-align: center;
        background-color: white;
    }
    
    .coupon-preview-code {
        font-size: 2rem;
        font-weight: bold;
        letter-spacing: 2px;
        margin-bottom: 1rem;
        color: #1f2937;
        text-transform: uppercase;
    }
    
    .coupon-preview-discount {
        font-size: 1.25rem;
        font-weight: 600;
        color: #10b981;
        margin-bottom: 1rem;
    }
    
    .coupon-preview-description {
        color: #6b7280;
        margin-bottom: 1rem;
    }
    
    .coupon-preview-expiry {
        font-size: 0.875rem;
        color: #6b7280;
    }
</style>

<script>
    // Same script as in the create page with slight modifications
    function toggleDiscountType(type) {
        const percentGroup = document.getElementById('percent_discount_group');
        const amountGroup = document.getElementById('amount_discount_group');
        
        if (type === 'percent') {
            percentGroup.style.display = 'block';
            amountGroup.style.display = 'none';
            document.getElementById('discount_amount').value = '';
        } else {
            percentGroup.style.display = 'none';
            amountGroup.style.display = 'block';
            document.getElementById('discount_percent').value = '';
        }
        
        updatePreview();
    }
    
    function updatePreview() {
        // Update code
        const codeInput = document.getElementById('code');
        const previewCode = document.getElementById('previewCode');
        previewCode.textContent = codeInput.value;
        
        // Update discount
        const previewDiscount = document.getElementById('previewDiscount');
        const percentInput = document.getElementById('discount_percent');
        const amountInput = document.getElementById('discount_amount');
        
        if (document.getElementById('amount_discount_group').style.display !== 'none' && amountInput.value) {
            previewDiscount.textContent = `${amountInput.value}€ de réduction`;
        } else if (percentInput.value) {
            previewDiscount.textContent = `${percentInput.value}% de réduction`;
        }
        
        // Update description
        const descriptionInput = document.getElementById('description');
        const previewDescription = document.getElementById('previewDescription');
        previewDescription.textContent = descriptionInput.value || 'Aucune description';
        
        // Update expiry
        const expiryInput = document.getElementById('expires_at');
        const previewExpiry = document.getElementById('previewExpiry');
        
        if (expiryInput.value) {
            const date = new Date(expiryInput.value);
            const formattedDate = date.toLocaleDateString('fr-FR', { 
                day: '2-digit', 
                month: '2-digit', 
                year: 'numeric' 
            });
            previewExpiry.textContent = `Expire le: ${formattedDate}`;
        } else {
            previewExpiry.textContent = 'Sans date d\'expiration';
        }
        
        // Update status
        const isActiveInput = document.getElementById('is_active');
        const previewStatus = document.querySelector('.coupon-preview-status');
        
        if (isActiveInput.checked) {
            previewStatus.textContent = 'Actif';
            previewStatus.classList.remove('inactive');
            previewStatus.classList.add('active');
        } else {
            previewStatus.textContent = 'Inactif';
            previewStatus.classList.remove('active');
            previewStatus.classList.add('inactive');
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Check initial discount type
        if (document.getElementById('amount_type').checked) {
            toggleDiscountType('amount');
        } else {
            toggleDiscountType('percent');
        }
    });
</script>
@endsection
