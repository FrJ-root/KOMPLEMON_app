<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">Total Produits</div>
        <div class="stat-value stat-products">124</div>
        <div class="stat-description">8 nouveaux ce mois</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-title">Utilisateurs enregistrés</div>
        <div class="stat-value stat-users">2,584</div>
        <div class="stat-description">↑ 12% par rapport au mois dernier</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-title">Commandes</div>
        <div class="stat-value stat-orders">463</div>
        <div class="stat-description">23 en attente</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-title">Chiffre d'affaires</div>
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
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>#ORD-8945</td>
                <td>Thomas Martin</td>
                <td><span class="badge badge-success">Terminée</span></td>
                <td>14 Mai, 2023</td>
                <td>€124.00</td>
            </tr>
            <tr>
                <td>#ORD-8944</td>
                <td>Sophie Laurent</td>
                <td><span class="badge badge-warning">En cours</span></td>
                <td>14 Mai, 2023</td>
                <td>€74.50</td>
            </tr>
            <tr>
                <td>#ORD-8943</td>
                <td>Jean Dupont</td>
                <td><span class="badge badge-danger">Remboursée</span></td>
                <td>13 Mai, 2023</td>
                <td>€249.99</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Actions rapides</h2>
    </div>
    
    <div class="quick-actions">
        <a href="/admin/users/create" class="quick-action">
            <span class="quick-action-icon">👤</span>
            <span>Ajouter un utilisateur</span>
        </a>
        <a href="/admin/coupons/create" class="quick-action">
            <span class="quick-action-icon">🎟️</span>
            <span>Créer un coupon</span>
        </a>
        <a href="/admin/settings" class="quick-action">
            <span class="quick-action-icon">⚙️</span>
            <span>Paramètres du site</span>
        </a>
        <a href="/admin/statistics" class="quick-action">
            <span class="quick-action-icon">📊</span>
            <span>Voir les statistiques</span>
        </a>
    </div>
</div>
