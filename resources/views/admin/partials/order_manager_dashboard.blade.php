<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">Commandes totales</div>
        <div class="stat-value stat-orders">463</div>
        <div class="stat-description">23 en attente</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-title">Clients</div>
        <div class="stat-value stat-customers">1,842</div>
        <div class="stat-description">↑ 5% par rapport au mois dernier</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-title">Commandes du jour</div>
        <div class="stat-value stat-today">12</div>
        <div class="stat-description">4 à traiter</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-title">Chiffre d'affaires mensuel</div>
        <div class="stat-value stat-revenue">€24,895</div>
        <div class="stat-description">↑ 8% par rapport au mois dernier</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Commandes récentes</h2>
        <a href="/admin/orders" class="view-all">Voir tout</a>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>Commande</th>
                <th>Client</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>#ORD-8945</td>
                <td>Thomas Martin</td>
                <td><span class="badge badge-success">Terminée</span></td>
                <td>14 Mai, 2023</td>
                <td>€124.00</td>
                <td>
                    <a href="/admin/orders/8945" class="btn-sm">Voir</a>
                </td>
            </tr>
            <tr>
                <td>#ORD-8944</td>
                <td>Sophie Laurent</td>
                <td><span class="badge badge-warning">En cours</span></td>
                <td>14 Mai, 2023</td>
                <td>€74.50</td>
                <td>
                    <a href="/admin/orders/8944" class="btn-sm">Voir</a>
                </td>
            </tr>
            <tr>
                <td>#ORD-8943</td>
                <td>Jean Dupont</td>
                <td><span class="badge badge-danger">Remboursée</span></td>
                <td>13 Mai, 2023</td>
                <td>€249.99</td>
                <td>
                    <a href="/admin/orders/8943" class="btn-sm">Voir</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Actions rapides</h2>
    </div>
    
    <div class="quick-actions">
        <a href="/admin/orders/export" class="quick-action">
            <span class="quick-action-icon">📤</span>
            <span>Exporter les commandes</span>
        </a>
        <a href="/admin/customers" class="quick-action">
            <span class="quick-action-icon">👥</span>
            <span>Gérer les clients</span>
        </a>
        <a href="/admin/orders/pending" class="quick-action">
            <span class="quick-action-icon">⏳</span>
            <span>Commandes en attente</span>
        </a>
    </div>
</div>
