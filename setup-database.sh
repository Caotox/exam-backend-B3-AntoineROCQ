#!/bin/bash

# Script d'installation complète de la base de données F1 Infractions
# Compatible MySQL (MAMP) et SQLite

echo "======================================"
echo "Setup Base de Données F1 Infractions"
echo "======================================"
echo ""

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "bin/console" ]; then
    echo "❌ Erreur: Ce script doit être exécuté depuis le répertoire f1-infractions-api"
    echo "   Usage: cd f1-infractions-api && ./setup-database.sh"
    exit 1
fi

echo "1️⃣  Vérification de l'environnement..."
if ! command -v php &> /dev/null; then
    echo "❌ PHP n'est pas installé"
    exit 1
fi
echo "✅ PHP $(php -v | head -n 1 | cut -d ' ' -f 2) détecté"

echo ""
echo "⚙️  Configuration détectée dans .env :"
grep "^DATABASE_URL=" .env || echo "   DATABASE_URL non trouvée"

echo ""
echo "2️⃣  Suppression de l'ancienne base de données (si elle existe)..."
php bin/console doctrine:database:drop --force --if-exists
echo "✅ Ancienne base supprimée (ou n'existait pas)"

echo ""
echo "3️⃣  Création de la nouvelle base de données..."
php bin/console doctrine:database:create
if [ $? -eq 0 ]; then
    echo "✅ Base de données créée"
else
    echo "❌ Erreur lors de la création de la base de données"
    echo ""
    echo "⚠️  Vérifiez que :"
    echo "   - MAMP est démarré"
    echo "   - La base de données B3-IN n'existe pas dans phpMyAdmin"
    echo "   - Les identifiants dans .env sont corrects (root:root@127.0.0.1:8889)"
    exit 1
fi

echo ""
echo "4️⃣  Exécution des migrations..."
php bin/console doctrine:migrations:migrate --no-interaction
if [ $? -eq 0 ]; then
    echo "✅ Migrations exécutées avec succès"
else
    echo "❌ Erreur lors de l'exécution des migrations"
    echo ""
    echo "⚠️  Les tables n'ont pas pu être créées."
    echo "   Vérifiez les logs ci-dessus pour plus de détails."
    exit 1
fi

echo ""
echo "5️⃣  Chargement des fixtures (données de test)..."
php bin/console doctrine:fixtures:load --no-interaction
if [ $? -eq 0 ]; then
    echo "✅ Fixtures chargées avec succès"
else
    echo "❌ Erreur lors du chargement des fixtures"
    echo ""
    echo "⚠️  Les données de test n'ont pas pu être chargées."
    echo "   Vérifiez les logs ci-dessus pour plus de détails."
    exit 1
fi

echo ""
echo "======================================"
echo "✅ Installation terminée avec succès !"
echo "======================================"
echo ""
echo "📊 Données créées dans la base B3-IN :"
echo "   ✓ 2 utilisateurs (admin@f1.com / user@f1.com)"
echo "   ✓ 4 moteurs"
echo "   ✓ 4 écuries"
echo "   ✓ 12 pilotes (3 par écurie)"
echo "   ✓ 4 infractions de test"
echo ""
echo "🚀 Démarrez le serveur avec :"
echo "   php -S 0.0.0.0:8000 -t public"
echo ""
echo "🧪 Testez avec Postman :"
echo "   Importez F1_Infractions_API.postman_collection.json"
echo ""
