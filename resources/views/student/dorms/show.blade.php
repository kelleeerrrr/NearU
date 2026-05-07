@extends('layouts.app')

@section('title', $listing->street . ' - NearU')

@push('styles')
<style>
.carousel { position: relative; overflow: hidden; border-radius: 18px; background: linear-gradient(135deg, #f8f9f7, #e8f5e8); box-shadow: 0 8px 32px rgba(45,125,79,0.12); }
.carousel-inner { display: flex; transition: transform .4s cubic-bezier(0.4, 0, 0.2, 1); width: 100%; }
.carousel-slide { min-width: 100%; flex-shrink: 0; position: relative; }
.carousel-slide img { width: 100%; height: 280px; object-fit: cover; display: block; }
.carousel-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 44px; height: 44px; border-radius: 50%; background: rgba(255,255,255,.95); border: none; box-shadow: 0 4px 20px rgba(0,0,0,.2); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 22px; color: #2D7D4F; z-index: 2; transition: all 0.3s ease; }
.carousel-arrow.prev { left: 16px; }
.carousel-arrow.next { right: 16px; }
.carousel-arrow:hover { transform: translateY(-50%) scale(1.1); background: #fff; box-shadow: 0 6px 24px rgba(0,0,0,.25); }
.carousel-indicator { position: absolute; bottom: 16px; right: 16px; background: rgba(45,125,79,0.9); color: #fff; padding: 8px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; backdrop-filter: blur(10px); }

.listing-header { background: linear-gradient(135deg, #2D7D4F, #4a9d6a); color: white; padding: 1.5rem; border-radius: 16px; margin-bottom: 1.5rem; box-shadow: 0 6px 24px rgba(45,125,79,0.2); }
.listing-title { font-size: 1.4rem; font-weight: 800; margin-bottom: 0.5rem; }
.listing-subtitle { opacity: 0.9; font-size: 0.95rem; }

.price-type-row { background: white; padding: 1.2rem; border-radius: 14px; margin-bottom: 1rem; box-shadow: 0 2px 12px rgba(0,0,0,0.08); display: flex; justify-content: space-between; align-items: center; }
.enhanced-price { font-size: 1.8rem; font-weight: 800; color: #2D7D4F; }
.enhanced-price small { font-size: 0.9rem; opacity: 0.8; }

.price-type.inc-box { 
  background: linear-gradient(135deg, #f8f9fa, #ffffff);
  padding: 1.2rem; 
  border-radius: 14px; 
  margin-bottom: 1rem; 
  box-shadow: 0 4px 12px rgba(0,0,0,0.08); 
  display: flex; 
  justify-content: space-between; 
  align-items: center; 
  border: 1px solid #e9ecef;
}

.inc-ttl { 
  font-size: 1.1rem; 
  font-weight: 700; 
  margin-bottom: 0.5rem; 
  color: #2D7D4F;
  text-shadow: 0 2px 4px rgba(45, 125, 79, 0.1);
}

.inc-grid { 
  display: flex; 
  flex-wrap: wrap; 
  gap: 0.5rem; 
  margin-bottom: 1.5rem; 
}

.inc-item {
  display: flex;
  align-items: center;
  gap: 0.3rem;
  padding: 0.4rem 0.6rem;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 8px;
  border: 1px solid rgba(255, 255, 255, 0.2);
  transition: all 0.2s ease;
}

.inc-item:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.inc-icon { 
  font-size: 1.2rem; 
  color: #2D7D4F;
}

.inc-text { 
  font-size: 0.9rem; 
  color: var(--t1);
  font-weight: 500;
}

.metas { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem; }
.mpill { padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; border-radius: 12px; background: #f8f9fa; border: 1px solid #e9ecef; transition: all 0.2s ease; }
.mpill:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.mpill.ok { background: linear-gradient(135deg, #2D7D4F, #4a9d6a); color: white; border: none; }

/* Button Styles */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.75rem 1.5rem;
  border-radius: 12px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  border: none;
  text-decoration: none;
  min-height: 44px;
  white-space: nowrap;
}

.btn-green {
  background: linear-gradient(135deg, #2D7D4F, #1f5c38);
  color: white;
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.2);
}

.btn-blue {
  background: linear-gradient(135deg, #007bff, #0056b3);
  color: white;
  box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
}

.btn-full {
  width: 100%;
}

.btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}
</style>
@endpush

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <!-- Back Button -->
      <div style="margin-bottom: 1rem;">
        <button class="icon-btn back-btn" onclick="history.back()" style="background: var(--green); color: white; border: none; padding: 0.6rem 1rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; transition: all 0.2s ease;">
          ← Back
        </button>
      </div>

      <!-- Listing Header -->
      <div class="listing-header">
        <h1 class="listing-title">{{ $listing->street }}</h1>
        <div class="listing-subtitle">{{ $listing->type }} • {{ $listing->gender_policy }} • {{ $listing->walk_minutes }} min walk to campus</div>
      </div>

      @php
        $images = $listing->images ?? collect([]);
        
        // Create gallery using proper storage path
        $gallery = $images->map(function($image) {
            return asset('storage/' . $image->path);
        })->values()->all();
      @endphp

      <!-- Image Carousel -->
      <div class="carousel" style="margin-bottom: 1rem;">
        <div class="carousel-inner" id="listing-carousel-inner">
          @forelse($gallery as $photo)
            <div class="carousel-slide">
              <img src="{{ $photo }}" alt="Dorm image">
            </div>
          @empty
            <div class="carousel-slide">
              <img src="https://via.placeholder.com/400x200?text=Dorm+Image" alt="Dorm image">
            </div>
          @endforelse
        </div>

        @if(count($gallery) > 1)
          <button type="button" class="carousel-arrow prev" id="carousel-prev">‹</button>
          <button type="button" class="carousel-arrow next" id="carousel-next">›</button>
        @endif

        <div class="carousel-indicator" id="carousel-indicator">1 / {{ count($gallery) ?: 1 }}</div>
      </div>

      <!-- Price and Type -->
      <div class="price-type-row">
        <div class="enhanced-price">₱{{ number_format($listing->price, 0) }}<small>/month</small></div>
        <div class="type-badge {{ $listing->type }}">{{ $listing->type }}</div>
      </div>

      <!-- Details -->
      <div class="metas" style="margin-bottom: 1rem;">
        <div class="mpill">{{ $listing->bathroom }}</div>
        <div class="mpill">{{ $listing->gender_policy }}</div>
        <div class="mpill">{{ $listing->walk_minutes }} min walk</div>
        @if($listing->wifi_included)
        <div class="mpill ok">WiFi Included</div>
        @endif
        @if($listing->pets_allowed)
        <div class="mpill">Pets Allowed</div>
        @endif
      </div>

      <!-- Includes -->
      @if($listing->furnishings || $listing->appliances || $listing->bills_included)
      <div class="inc-box" style="margin-bottom: 1rem;">
        <div class="inc-ttl">What's Included</div>
        <div class="inc-grid">
          @if($listing->furnishings)
            @php
              $furnishings = is_array($listing->furnishings) ? $listing->furnishings : (json_decode($listing->furnishings, true) ?: []);
            @endphp
            @foreach($furnishings as $furnishing)
              @if(!empty($furnishing))
              <div class="inc-item">
                <span class="inc-icon">🛋️</span>
                <span class="inc-text">{{ $furnishing }}</span>
              </div>
              @endif
            @endforeach
          @endif
          
          @if($listing->appliances)
            @php
              $appliances = (is_array($listing->appliances)) ? $listing->appliances : ((json_decode($listing->appliances, true)) ?: []);
            @endphp
            @foreach($appliances as $appliance)
              @if(!empty($appliance))
              <div class="inc-item">
                <span class="inc-icon">🔌</span>
                <span class="inc-text">{{ $appliance }}</span>
              </div>
              @endif
            @endforeach
          @endif
          
          @if($listing->bills_included)
            @php
              $bills = is_array($listing->bills_included) ? $listing->bills_included : (json_decode($listing->bills_included, true) ?: []);
            @endphp
            @foreach($bills as $bill)
              @if(!empty($bill))
              <div class="inc-item">
                <span class="inc-icon">💡</span>
                <span class="inc-text">{{ $bill }}</span>
              </div>
              @endif
            @endforeach
          @endif
        </div>
      </div>
      @endif

      <!-- House Rules -->
      @if($listing->curfew)
      <div class="inc-box" style="margin-bottom: 1rem;">
        <div class="inc-ttl">House Rules</div>
        <div class="inc-grid">
          <div class="inc-i">🕐 Curfew: {{ $listing->curfew }}</div>
        </div>
      </div>
      @endif

      <!-- Owner Info -->
      <div class="owner-chip" style="margin-bottom: 1rem;">
        👤 Owner: {{ $listing->owner->name }}
        @if($listing->owner->phone)
        📞 {{ $listing->owner->phone }}
        @endif
      </div>

      <!-- Action Buttons -->
      <div class="btn-row" style="margin-bottom: 5rem;">
        @auth
        <a href="{{ route('messages.show', [$listing->id, $listing->owner->id]) }}" class="btn btn-blue btn-full">💬 Message Owner</a>
        @endauth
      </div>
    </div>
  </div>

  @include('partials.footer')
</div>

<!-- Add bottom spacing for floating nav bar -->
<div style="height: 6rem;"></div>
@endsection

@push('scripts')
<script>

const Schedule = {
  _selectedTime: null,
  _dormId: null,

  open(dormId, street, type, price, owner) {
    this._dormId = dormId;
    this._selectedTime = null;
    document.querySelectorAll('.tslot').forEach(b => b.classList.remove('sel'));
    document.getElementById('schedDormId').value = dormId;
    document.getElementById('vNotes').value      = '';
    document.getElementById('vDate').value       = '';
    document.getElementById('schedInfo').innerHTML =
      `<strong>${street}</strong> — ${type}<br>
       <span style="color:var(--t2);">₱${Number(price).toLocaleString()}/mo · 👤 ${owner || 'Owner'}</span>`;
    document.getElementById('schedModal').classList.add('active');
  },

  selTime(el) {
    document.querySelectorAll('.tslot').forEach(b => b.classList.remove('sel'));
    el.classList.add('sel');
    this._selectedTime = el.textContent.trim();
  },

  submit() {
    const dt    = document.getElementById('vDate').value;
    const notes = document.getElementById('vNotes').value;
    if (!dt)                 { showToast('⚠️ Select a date', 'warn');      return; }
    if (!this._selectedTime) { showToast('⚠️ Select a time slot', 'warn'); return; }

    fetch('/visits', {
      method:'POST',
      headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':window.csrfToken },
      body: JSON.stringify({ dorm_listing_id:this._dormId, visit_date:dt, visit_time:this._selectedTime, notes }),
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        document.getElementById('schedModal').classList.remove('active');
        showToast(`✅ Visit confirmed — ${dt} at ${this._selectedTime}!`, 'ok');
      } else {
        showToast('⚠️ ' + (data.message || 'Could not schedule'), 'warn');
      }
    })
    .catch(() => showToast('⚠️ Could not schedule visit', 'warn'));
  },
};

function scheduleVisit(dormId) {
  const date = prompt('Enter visit date (YYYY-MM-DD):');
  const time = prompt('Enter visit time (HH:MM):');
  if (date && time) {
    fetch('/visits', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.csrfToken
      },
      body: JSON.stringify({ dorm_listing_id: dormId, visit_date: date, visit_time: time, notes: '' })
    }).then(r => r.json())
    .then(data => {
      if (data.success) {
        showToast(`✅ Visit confirmed — ${date} at ${time}!`, 'ok');
      } else {
        showToast('⚠️ ' + (data.message || 'Could not schedule'), 'warn');
      }
    })
    .catch(() => showToast('⚠️ Could not schedule visit', 'warn'));
  }
}

document.addEventListener('DOMContentLoaded', function() {
  const inner = document.getElementById('listing-carousel-inner');
  const prevBtn = document.getElementById('carousel-prev');
  const nextBtn = document.getElementById('carousel-next');
  const indicator = document.getElementById('carousel-indicator');
  if (!inner) return;

  const slides = inner.querySelectorAll('.carousel-slide');
  const total = slides.length;
  let index = 0;

  function updateCarousel() {
    inner.style.transform = `translateX(-${index * 100}%)`;
    if (indicator) {
      indicator.textContent = `${index + 1} / ${total}`;
    }
    if (prevBtn) {
      prevBtn.style.display = total > 1 ? 'flex' : 'none';
    }
    if (nextBtn) {
      nextBtn.style.display = total > 1 ? 'flex' : 'none';
    }
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', function() {
      index = (index - 1 + total) % total;
      updateCarousel();
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', function() {
      index = (index + 1) % total;
      updateCarousel();
    });
  }

  updateCarousel();
});
</script>
@endpush