<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Dashboard" parent="Dashboard" child="List" />
    
    <style>
        /* System Overview Cards */
        .overview-card {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            height: 100%;
        }
        .overview-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }
        .card-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            border-radius: 12px;
            font-size: 1.5rem;
            margin-right: 1.25rem;
            transition: all 0.3s ease;
        }
        .overview-card:hover .card-icon {
            transform: scale(1.1);
        }
        .card-details {
            display: flex;
            flex-direction: column;
        }
        .card-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #828a96;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }
        .card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }

        /* Colors for Overview */
        .card-blue { border-top: 4px solid #3b82f6; }
        .card-blue .card-icon { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }

        .card-purple { border-top: 4px solid #8b5cf6; }
        .card-purple .card-icon { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

        .card-teal { border-top: 4px solid #0f766e; }
        .card-teal .card-icon { background: rgba(15, 118, 110, 0.1); color: #0f766e; }

        /* Minimalist Stage Status Cards (Wide & Tall) */
        .status-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.75rem 2rem;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            min-height: 100px;
            height: 100%;
            transition: all 0.3s ease;
        }
        .status-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }
        .status-info {
            display: flex;
            align-items: center;
        }
        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 1rem;
        }
        .status-name {
            font-size: 1.05rem;
            font-weight: 600;
            color: #475569;
        }
        .status-count {
            font-size: 1.4rem;
            font-weight: 800;
            padding: 0.35rem 0.95rem;
            border-radius: 10px;
        }

        /* Status Colors */
        .border-draft { border-left: 5px solid #f59e0b; }
        .border-draft .status-dot { background: #f59e0b; }
        .border-draft .status-count { background: rgba(245, 158, 11, 0.1); color: #d97706; }

        .border-shelfing { border-left: 5px solid #64748b; }
        .border-shelfing .status-dot { background: #64748b; }
        .border-shelfing .status-count { background: rgba(100, 116, 139, 0.1); color: #475569; }

        .border-implementation { border-left: 5px solid #10b981; }
        .border-implementation .status-dot { background: #10b981; }
        .border-implementation .status-count { background: rgba(16, 185, 129, 0.1); color: #059669; }
    </style>

    <div class="card mb-4 shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 font-weight-bold text-dark">WELCOME TO {{ $siteAdmin->name ?? 'Reform Initiatives' }}</h5>
        </div>

        <div class="card-body text-justify text-muted">
            <p class="mb-0">{{ $siteAdmin->aboutus ?? 'To implement and monitor reform initiatives roadmap to enhance the institutional efficiency and effectiveness of the Ministry of Revenues.' }}</p>
        </div>
    </div>

    <!-- Core Metrics Row -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
            <div class="overview-card card-blue">
                <div class="card-icon">
                    <i class="fas fa-sitemap"></i>
                </div>
                <div class="card-details">
                    <span class="card-title">Directorates</span>
                    <span class="card-value">{{ $directoratesCount }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
            <div class="overview-card card-purple">
                <div class="card-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <div class="card-details">
                    <span class="card-title">Total Initiatives</span>
                    <span class="card-value">{{ $initiativesCount }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
            <div class="overview-card card-teal">
                <div class="card-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="card-details">
                    <span class="card-title">Partners</span>
                    <span class="card-value">{{ $partnersCount }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stage Status Cards Row -->
    <div class="row">
        <!-- Draft Stage -->
        <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
            <div class="status-card border-draft">
                <div class="status-info">
                    <span class="status-dot"></span>
                    <span class="status-name">Draft Stage</span>
                </div>
                <span class="status-count">{{ $draftCount }}</span>
            </div>
        </div>

        <!-- Shelfing Stage -->
        <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
            <div class="status-card border-shelfing">
                <div class="status-info">
                    <span class="status-dot"></span>
                    <span class="status-name">Shelfing Stage</span>
                </div>
                <span class="status-count">{{ $shelfingCount }}</span>
            </div>
        </div>

        <!-- Implementation Stage -->
        <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
            <div class="status-card border-implementation">
                <div class="status-info">
                    <span class="status-dot"></span>
                    <span class="status-name">Implementation Stage</span>
                </div>
                <span class="status-count">{{ $implementationCount }}</span>
            </div>
        </div>
    </div>
</x-layout>
