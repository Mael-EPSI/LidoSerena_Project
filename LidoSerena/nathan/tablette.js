document.addEventListener("DOMContentLoaded", function () {
    chargerProduits();
    chargerMenus();
});

// Fonction pour charger les produits depuis l'API
// Déclarer ceci en global, par exemple en haut de votre fichier tablette.js
let productMap = {};
let commandes = {};
let refreshInterval;
let notificationInterval;

function chargerProduits() {
    fetch("http://localhost/lidoserena/api/produits.php")
        .then(response => response.json())
        .then(data => {
            // Remplir productMap : association nom du produit -> prix
            data.forEach(prod => {
                productMap[prod.nom] = prod.prix;
            });

            let container = document.getElementById("produits-container");
            container.innerHTML = ""; // Nettoyer avant d'ajouter les nouveaux produits

            let categories = {
                16: "Pizza",
                17: "Boisson",
                18: "Dessert",
                19: "Plat",
                20: "Pâtes"
            };

            // Parcourir chaque catégorie et y insérer les produits correspondants
            Object.keys(categories).forEach(cat_id => {
                let categorySection = document.createElement("div");
                categorySection.classList.add("category-section");

                let title = document.createElement("h2");
                title.textContent = categories[cat_id];
                categorySection.appendChild(title);

                let productsContainer = document.createElement("div");
                productsContainer.classList.add("products-container");

                // Insérer les produits de cette catégorie
                data.forEach(produit => {
                    if (produit.categorie_id == cat_id) {
                        let button = document.createElement("button");
                        button.classList.add("produit-btn");
                        button.dataset.id = produit.id;
                        button.dataset.prix = produit.prix;
                        button.dataset.nom = produit.nom;
                        button.dataset.cat_id = produit.categorie_id;
                        button.textContent = `${produit.nom} - ${produit.prix}€`;

                        // Ajouter un gestionnaire d'événement pour l'ajout au panier
                        button.addEventListener("click", ajouterProduitAuPanier);

                        productsContainer.appendChild(button);
                    }
                });

                if (productsContainer.childNodes.length > 0) {
                    categorySection.appendChild(productsContainer);
                    container.appendChild(categorySection);
                }
            });
        })
        .catch(error => {
            console.error("Erreur lors de la récupération des produits:", error);
            alert("Impossible de charger les produits. Essayez encore.");
        });
}

// Fonction pour charger les menus depuis l'API
function chargerMenus() {
    fetch("http://localhost/lidoserena/api/get_menus.php")
        .then(response => response.json())
        .then(data => {
            let container = document.getElementById("menus-container");
            container.innerHTML = ""; // Nettoyer avant d'ajouter les nouveaux menus

            data.forEach(menu => {
                console.log("📌 Vérification menu reçu :", menu); // ✅ DEBUG

                let button = document.createElement("button");
                button.classList.add("menu-btn");

                // Vérification et correction de l'ID
                if (!menu.id) {
                    console.error("❌ Erreur: ID du menu est undefined pour :", menu);
                    return; // Ne pas ajouter ce menu si l'ID est absent
                }

                button.dataset.id = menu.id;
                button.dataset.prix = menu.prix;
                button.dataset.nom = menu.nom;
                button.dataset.categories = JSON.stringify(menu.categories); // On ajoute les catégories

                button.textContent = menu.nom;

                button.addEventListener("click", ajouterMenuAuPanier);
                container.appendChild(button);
            });
        })
        .catch(error => {
            console.error("❌ Erreur lors de la récupération des menus:", error);
            alert("Impossible de charger les menus. Essayez encore.");
        });
}

function chargerCommande() {
    fetch("http://localhost/lidoserena/api/get_commande.php")
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                commandes = data.commandes.reduce((acc, commande) => {
                    if (!acc[commande.table_id]) {
                        acc[commande.table_id] = [];
                    }
                    acc[commande.table_id].push(commande);
                    return acc;
                }, {});
                updateCommandes(); // 🔥 Mettre à jour l'affichage
            } else {
                console.error("Erreur lors de la récupération des commandes.");
                alert("Erreur lors de la récupération des commandes.");
            }
        })
        .catch(error => {
            console.error("🚨 Erreur lors de la récupération de la commande :", error);
            alert("Impossible de charger la commande. Essayez encore.");
        });
}

function updateCommandes() {
    document.querySelectorAll('.section').forEach(section => {
        const tableId = section.getAttribute('data-table-id');
        const commandeContainer = section.querySelector('.commande');
        const totalElement = section.querySelector('.total');

        commandeContainer.innerHTML = "";
        let total = 0;

        if (commandes[tableId] && commandes[tableId].length > 0) {
            commandes[tableId].forEach(commande => {
                const commandeElement = document.createElement('div');
                commandeElement.classList.add('commande-item');

                // Affichage des menus avec leurs produits
                let menusHTML = '';
                if (commande.menus && commande.menus.length > 0) {
                    menusHTML = `<h4>Menus :</h4><ul>`;
                    commande.menus.forEach(menu => {
                        total += parseFloat(menu.prix || 0);
                        menusHTML += `
                            <li>
                                ${menu.nom} - ${parseFloat(menu.prix || 0).toFixed(2)}€
                                ${menu.produits && menu.produits.length > 0 ? `
                                    <ul class="menu-produits">
                                        ${menu.produits.map(produit => 
                                            `<li class="menu-produit">• ${produit}</li>`
                                        ).join('')}
                                    </ul>
                                ` : ''}
                            </li>
                        `;
                    });
                    menusHTML += `</ul>`;
                }

                // Affichage des produits individuels
                let produitsHTML = '';
                if (commande.produits && commande.produits.length > 0) {
                    produitsHTML = `<h4>Produits :</h4><ul>`;
                    commande.produits.forEach(produit => {
                        let prix = typeof produit === 'object' ? produit.prix : (productMap[produit] || 0);
                        let nom = typeof produit === 'object' ? produit.nom : produit;
                        total += parseFloat(prix);
                        produitsHTML += `<li>${nom} - ${parseFloat(prix).toFixed(2)}€</li>`;
                    });
                    produitsHTML += `</ul>`;
                }

                commandeElement.innerHTML = `
                    <p>Commande #${commande.commande_id} - Statut : <strong>${commande.statut}</strong></p>
                    ${menusHTML}
                    ${produitsHTML}
                `;

                commandeContainer.appendChild(commandeElement);
            });

            totalElement.textContent = `Total : ${total.toFixed(2)}€`;
        } else {
            commandeContainer.innerHTML = "<p>Aucune commande en cours pour cette table.</p>";
            totalElement.textContent = "Total : 0€";
        }
    });
}

function ajouterProduitAuPanier() {
    const activeSection = document.querySelector('.section.active');
    if (!activeSection) return;

    const totalElement = activeSection.querySelector('.total');
    const commandeContainer = activeSection.querySelector('.commande');

    const prix = parseFloat(this.dataset.prix) || 0;
    const nom = this.dataset.nom || "Produit";
    const id = this.dataset.id || null; // Récupération de l'ID du produit

    if (!id) {
        console.error("❌ Erreur : Produit sans ID détecté !");
        return;
    }

    const total = parseFloat(totalElement.textContent.replace("Total :", "").trim()) || 0;
    totalElement.textContent = "Total : " + (total + prix).toFixed(2) + "€";

    const produitItem = document.createElement('div');
    produitItem.classList.add('produit-item');
    produitItem.dataset.id = id; // Ajout de l'ID
    produitItem.dataset.prix = prix; // Ajout du prix
    produitItem.textContent = `${nom} - ${prix.toFixed(2)}€`;

    // Ajouter un bouton de suppression
    const deleteBtn = document.createElement('button');
    deleteBtn.textContent = '❌';
    deleteBtn.classList.add('delete-btn');
    deleteBtn.addEventListener('click', function () {
        produitItem.remove();
        const totalActuel = parseFloat(totalElement.textContent.replace("Total :", "").trim()) || 0;
        totalElement.textContent = "Total : " + (totalActuel - prix).toFixed(2) + "€";
    });

    produitItem.appendChild(deleteBtn);
    commandeContainer.appendChild(produitItem);

    console.log("✅ Produit ajouté au panier :", {
        id: produitItem.dataset.id,
        prix: produitItem.dataset.prix,
        nom: nom
    });
}

// Fonction pour ajouter un menu au panier
function ajouterMenuAuPanier() {
    const activeSection = document.querySelector('.section.active');
    if (!activeSection) return;

    const totalElement = activeSection.querySelector('.total');
    const commandeContainer = activeSection.querySelector('.commande');

    const prix = parseFloat(this.dataset.prix) || 0;
    const id = this.dataset.id;
    const nom = this.dataset.nom || "Menu";
    const categoriesAutorisees = JSON.parse(this.dataset.categories);

    // ✅ Vérification avant d'ajouter
    if (!id || prix === 0) {
        console.error("❌ Erreur : Menu sans ID ou prix détecté !");
        alert("❌ Problème avec le menu sélectionné.");
        return;
    }

    console.log("🛠️ Catégories du menu sélectionné :", categoriesAutorisees);

    // Récupérer les plats correspondants
    fetch("http://localhost/lidoserena/api/produits.php")
        .then(response => response.json())
        .then(data => {
            let produitsFiltres = data.filter(produit => categoriesAutorisees.includes(parseInt(produit.categorie_id)));

            console.log("🎯 Produits disponibles pour ce menu :", produitsFiltres);

            if (produitsFiltres.length === 0) {
                alert("⚠️ Aucun plat disponible pour ce menu.");
                return;
            }

            // 🏆 Affichage du pop-up avec les choix possibles
            const popup = document.getElementById("menu-popup");
            const popupTitle = document.getElementById("popup-title");
            const popupOptions = document.getElementById("popup-options");
            const popupConfirm = document.getElementById("popup-confirm");
            const popupClose = document.getElementById("popup-close");

            popupTitle.textContent = `Choisissez vos plats pour ${nom}`;
            popupOptions.innerHTML = "";

            let choixUtilisateur = {}; // Stocker les choix

            categoriesAutorisees.forEach(cat => {
                let produitsCat = produitsFiltres.filter(p => p.categorie_id == cat);
                if (produitsCat.length > 0) {
                    let categoryDiv = document.createElement("div");
                    categoryDiv.classList.add("category-section");

                    let categoryTitle = document.createElement("h4");
                    categoryTitle.textContent = `Choisissez un plat de la catégorie ${cat}`;
                    categoryDiv.appendChild(categoryTitle);

                    let optionsContainer = document.createElement("div");
                    optionsContainer.classList.add("options-container");

                    produitsCat.forEach(produit => {
                        let optionButton = document.createElement("button");
                        optionButton.classList.add("option-btn");
                        optionButton.textContent = `${produit.nom} (${produit.prix}€)`;
                        optionButton.dataset.produitId = produit.id;

                        // Sélection d'un seul plat par catégorie
                        optionButton.addEventListener("click", function () {
                            optionsContainer.querySelectorAll(".option-btn.selected").forEach(btn => {
                                btn.classList.remove("selected");
                            });
                            this.classList.add("selected");
                            choixUtilisateur[cat] = produit.id;
                        });

                        optionsContainer.appendChild(optionButton);
                    });

                    categoryDiv.appendChild(optionsContainer);
                    popupOptions.appendChild(categoryDiv);
                }
            });

            // Afficher le pop-up
            popup.style.display = "flex";

            // Bouton Annuler
            popupClose.onclick = function () {
                popup.style.display = "none";
            };

            // Bouton Valider
            popupConfirm.onclick = function () {
                if (Object.keys(choixUtilisateur).length !== categoriesAutorisees.length) {
                    alert("Veuillez sélectionner un plat par catégorie.");
                    return;

                }

                console.log("✅ Choix utilisateur :", choixUtilisateur);

                // Ajouter au panier
                const total = parseFloat(totalElement.textContent.replace("Total :", "").trim()) || 0;
                totalElement.textContent = "Total : " + (total + prix).toFixed(2) + "€";

                const menuItem = document.createElement('div');
                menuItem.classList.add('menu-item');
                menuItem.textContent = `${nom} - ${prix.toFixed(2)}€`;
                menuItem.dataset.id = id;   // ✅ On ajoute l'ID du menu
                menuItem.dataset.prix = prix; // ✅ On ajoute le prix du menu

                // Ajout des choix de plats dans les datasets pour l'API
                menuItem.dataset.produits = JSON.stringify(Object.values(choixUtilisateur));

                // Ajouter un bouton de suppression
                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = '❌';
                deleteBtn.classList.add('delete-btn');
                deleteBtn.addEventListener('click', function () {
                    menuItem.remove();
                    const totalActuel = parseFloat(totalElement.textContent.replace("Total :", "").trim()) || 0;
                    totalElement.textContent = "Total : " + (totalActuel - prix).toFixed(2) + "€";
                });

                menuItem.appendChild(deleteBtn);
                commandeContainer.appendChild(menuItem);

                // Masquer le pop-up après validation
                popup.style.display = "none";
            };
        })
        .catch(error => {
            console.error("❌ Erreur lors du filtrage des produits:", error);
            alert("Impossible de charger les produits du menu. Essayez encore.");
        });
}

document.addEventListener("DOMContentLoaded", function () {
    chargerProduits();
    chargerMenus();
    chargerCommande();
    // Démarrer l'auto-refresh
    startAutoRefresh();
    // Démarrer l'auto-refresh des notifications
    startNotificationRefresh();
    document.querySelectorAll('.envoyer-commande-btn').forEach(button => {
        button.addEventListener("click", envoyerCommande);
    });
});

// Fonction pour démarrer l'auto-refresh
function startAutoRefresh() {
    // Refresh toutes les 30 secondes
    refreshInterval = setInterval(() => {
        chargerCommande();
        showToastNotification();
    }, 30000); // 30000 ms = 30 secondes
}

// Fonction pour démarrer l'auto-refresh des notifications
function startNotificationRefresh() {
    // Premier appel immédiat
    showToastNotification();
    // Puis toutes les 30 secondes
    notificationInterval = setInterval(showToastNotification, 30000);
}

// Fonction pour arrêter l'auto-refresh si nécessaire
function stopAutoRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
}

// Fonction pour arrêter l'auto-refresh des notifications
function stopNotificationRefresh() {
    if (notificationInterval) {
        clearInterval(notificationInterval);
    }
}

// Ajouter cette ligne avant la fermeture de la fenêtre pour nettoyer
window.addEventListener('beforeunload', () => {
    stopAutoRefresh();
    stopNotificationRefresh();
});

function envoyerCommande() {
    const activeSection = document.querySelector('.section.active');
    if (!activeSection) {
        alert("❌ Aucune table active sélectionnée !");
        return;
    }

    const tableId = activeSection.dataset.tableId;
    const commandeContainer = activeSection.querySelector('.commande');
    const produits = [];
    const menus = [];

    // Récupérer les produits avec leurs prix
    commandeContainer.querySelectorAll('.produit-item:not(.commande-item)').forEach(item => {
        const id = item.dataset.id;
        const prix = parseFloat(item.dataset.prix);
        if (id && prix) {
            produits.push({ id, prix }); // Ajout du prix avec l'ID
        }
    });

    // Récupérer les menus avec leurs prix
    commandeContainer.querySelectorAll('.menu-item:not(.commande-item)').forEach(item => {
        const id = item.dataset.id;
        const prix = parseFloat(item.dataset.prix);
        if (id && prix) {
            let produitsMenu = [];
            try {
                produitsMenu = JSON.parse(item.dataset.produits);
            } catch (error) {
                console.error("❌ Erreur parsing produits menu:", error);
            }
            menus.push({ id, prix, produits: produitsMenu }); // Ajout du prix avec l'ID
        }
    });

    // Vérification des données
    if (produits.length === 0 && menus.length === 0) {
        alert("❌ Aucun nouveau produit ou menu à ajouter !");
        return;
    }

    const commandeId = commandes[tableId]?.[commandes[tableId].length - 1]?.commande_id;

    const payload = {
        table_id: tableId,
        produits: produits,
        menus: menus,
        commande_id: commandeId
    };

    console.log("📤 Données envoyées à l'API :", JSON.stringify(payload, null, 2));

    // 🔹 Envoi des données à l'API
    fetch("http://localhost/lidoserena/api/envoyer_commande.php", {
        method: "POST",
        mode: "cors",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("✅ Commande mise à jour avec succès !");
            // Marquer les éléments comme envoyés
            commandeContainer.querySelectorAll('.produit-item:not(.commande-item), .menu-item:not(.commande-item)')
                .forEach(item => item.classList.add('commande-item'));
            // Recharger les commandes pour mettre à jour l'affichage
            chargerCommande();
        } else {
            alert("❌ Erreur API : " + data.message);
        }
    })
    .catch(error => console.error("❌ Erreur fetch :", error));
}

// Fonction pour gérer le paiement avec double confirmation
document.addEventListener('click', function (event) {
    if (event.target.classList.contains('payer-btn')) {
        const button = event.target;
        const activeSection = document.querySelector('.section.active');
        if (!activeSection) return;

        const tableId = activeSection.dataset.tableId;
        if (!tableId || !commandes[tableId] || commandes[tableId].length === 0) {
            alert("Aucune commande active pour cette table.");
            return;
        }

        // Récupérer la dernière commande pour cette table
        const dernierCommande = commandes[tableId][commandes[tableId].length - 1];
        const commandeId = dernierCommande.commande_id;

        console.log("🔍 ID de la table et de la commande :", tableId, commandeId);
        
        if (!commandeId) {
            alert("Impossible de trouver l'ID de la commande.");
            return;
        }

        const totalElement = activeSection.querySelector('.total');
        const commandeContainer = activeSection.querySelector('.commande');

        if (button.dataset.confirmed === "true") {
            // Confirmation : on envoie la requête pour marquer la commande comme payée
            fetch("http://localhost/lidoserena/api/payer.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ commande_id: commandeId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log("Commande payée avec succès !");
                        // Mettre à jour l'affichage
                        totalElement.textContent = 'Total : 0€';
                        if (commandeContainer) {
                            commandeContainer.innerHTML = "";
                        }
                        // Mettre à jour les commandes locales
                        delete commandes[tableId];
                    } else {
                        alert("Erreur lors du paiement: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Erreur fetch:", error);
                    alert("Erreur lors du paiement. Veuillez réessayer.");
                });

            button.dataset.confirmed = "false";
            button.textContent = "Payer";
        } else {
            button.dataset.confirmed = "true";
            button.textContent = "Cliquez à nouveau pour confirmer";
            setTimeout(() => {
                if (button.dataset.confirmed === "true") {
                    button.dataset.confirmed = "false";
                    button.textContent = "Payer";
                }
            }, 5000);
        }
    }
});

// Fonction pour afficher une section active
function showSection(sectionId) {
    document.querySelectorAll('.section').forEach(section => section.classList.remove('active'));
    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.classList.add('active');
    }
}

// Fonction pour afficher une notification toast cliquable
function showToastNotification() {
    fetch('http://localhost/lidoserena/api/get_notification.php')
      .then(response => response.json())
      .then(data => {
        if (!data.success || !data.notifications || data.notifications.length === 0) {
          console.log('Aucune nouvelle notification');
          return;
        }

        // On prend la première notification non lue
        const notification = data.notifications[0];
        const message = notification.message || "Nouvelle notification";
        
        console.log('Notification reçue :', notification);
        
        // Création de l'élément toast
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerText = message;
  
        toast.addEventListener('click', () => {
          fetch('http://localhost/lidoserena/api/marquer_lu.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ notification_id: notification.id })
          })
            .then(response => response.json())
            .then(result => {
              console.log('Notification validée :', result);
              toast.remove();
            })
            .catch(error => {
              console.error('Erreur lors de la validation de la notification:', error);
            });
        });
  
        document.body.appendChild(toast);
  
        setTimeout(() => {
          if (toast.parentElement) {
            toast.remove();
          }
        }, 5000);
      })
      .catch(error => console.error('Erreur lors de la récupération de la notification:', error));
}

// Exemple d'appel de la fonction
showToastNotification();