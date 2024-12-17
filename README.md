---

# **TechSouq - Projet WordPress**  

## **Description du projet**  
TechSouq est un projet basé sur **WordPress** visant à fournir une plateforme technologique intuitive pour des projets web. Ce projet inclut un site WordPress personnalisé avec une base de données SQL et une configuration prête pour le développement local.  

---

## **Prérequis**  
Avant de commencer, assurez-vous d'avoir les outils suivants installés :  
- [XAMPP](https://www.apachefriends.org) ou [WAMP](https://www.wampserver.com/) (serveur local pour Apache, MySQL, et PHP)  
- WordPress (fichiers du site)  
- Navigateur web (ex. Google Chrome)  

---

## **Étapes d'installation**  

### **1. Transférer les fichiers dans `htdocs`**  
1. Téléchargez le dossier TechSouq contenant les fichiers de votre projet WordPress (fichiers `.php`, dossiers `wp-content`, etc.).  
2. Rendez-vous dans le dossier d'installation de XAMPP ou WAMP sur votre ordinateur :  
   - **Windows :** `C:\xampp\htdocs\`  
   - **Mac :** `/Applications/XAMPP/htdocs/`  
3. Copiez votre dossier de projet WordPress dans `htdocs`.  
4. Renommez le dossier en `TechSouq` pour une identification claire.  

---

### **2. Importer la base de données SQL dans phpMyAdmin**  
1. Lancez **XAMPP** ou **WAMP** et démarrez les services suivants :  
   - **Apache** (serveur web)  
   - **MySQL** (base de données)  
2. Ouvrez votre navigateur web et accédez à `http://localhost/phpmyadmin`.  
3. Créez une nouvelle base de données :  
   - Cliquez sur **Nouvelle** dans phpMyAdmin.  
   - Donnez un nom à votre base de données, par exemple : `techsouq`.  
   - Cliquez sur **Créer**.  
4. Importez le fichier SQL :  
   - Dans le menu de la base de données, cliquez sur **Importer**.  
   - Sélectionnez votre fichier SQL (`techsouq.sql`) depuis votre ordinateur.  
   - Cliquez sur **Exécuter** pour importer la base de données.  

---

### **3. Configurer l'identifiant et le mot de passe**  
1. Ouvrez le fichier **wp-config.php** situé à la racine de votre projet WordPress (dans `htdocs/techsouq`).  
2. Localisez les lignes suivantes :  

   ```php  
   define('DB_NAME', 'techsouq_db'); // Nom de la base de données  
   define('DB_USER', 'root');        // Identifiant MySQL  
   define('DB_PASSWORD', '');        // Mot de passe MySQL (laisser vide par défaut sur XAMPP)  
   define('DB_HOST', 'localhost');   // Serveur de base de données  
   ```  
3. Modifiez les valeurs selon vos paramètres :  
   - **DB_NAME** : Le nom de votre base de données (ex. `techsouq_db`)  
   - **DB_USER** : Par défaut, `root` pour XAMPP/WAMP.  
   - **DB_PASSWORD** : Laissez vide pour XAMPP ou configurez votre mot de passe si nécessaire.  
4. Enregistrez le fichier et fermez-le.  

---

### **4. Accéder au projet WordPress**  
1. Ouvrez votre navigateur et accédez à l'URL suivante :  
   ```  
   http://localhost/techsouq  
   ```  
2. Le site WordPress devrait s'afficher correctement.  

---

### **5. Accéder au projet WordPress mode admin**  
Pour accéder au tableau de bord d'administration WordPress, utilisez les informations suivantes :  

- **URL :** `http://localhost/techsouq/wp-admin`  
- **Identifiant :** `ADIA_G3`  
- **Mot de passe :** `^rWEB6YTJjE^LHTkgM`  

---

## **Informations supplémentaires**  
- Pour accéder à **phpMyAdmin**, utilisez :  
  - URL : `http://localhost/phpmyadmin`  
  - Identifiant : `root`  
  - Mot de passe : *(vide par défaut sur XAMPP)*  
- Pour des raisons de sécurité, configurez un mot de passe pour l'utilisateur MySQL si le projet est déployé en production.  

---

## **Auteurs**  
- **Malih Salah**  
- **Kamal LOTFI**  
- **Soufiane TIDRARINI**  
- **Supervision : *Hmedna Brahim***  

---

## **Licence**  
Ce projet est sous licence MIT. Consultez le fichier `LICENSE` pour plus de détails.  

---
