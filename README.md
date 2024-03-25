# Display_Score_Web

QString getInfoRank(const QString &user, const QString &title);
    QString getNewHighscore(QString userName,QString title);


    QString Sgdb::getNewHighscore(QString userName, QString title) {
   
    if (!db.open()) {
        qDebug() << "Impossible d'ouvrir la base de données. getNewHighscore";
            return "Database connection error";
    } else {
        qDebug() << "Ouverture de la base de données SDGB.";
    }

    // Préparer la requête SQL avec des paramètres liés
    QSqlQuery query;
    query.prepare("SELECT u.Pseudo AS Joueur, s.Score AS Meilleur_Score FROM scores s JOIN utilisateurs u ON s.ID_utilisateur = u.ID_utilisateur JOIN jeux j ON s.ID_jeu = j.ID_jeu WHERE j.Nom_jeu = :title AND u.Pseudo = :userName ORDER BY s.Score DESC LIMIT 1;");
    query.bindValue(":title", title);
    query.bindValue(":userName", userName);

    // Exécuter la requête
    if (!query.exec()) {
        qDebug() << "Erreur lors de l'exécution de la requête : " << query.lastError() << "getNewHighscore";
        return "Query execution error";
    }

    // Récupérer le résultat (le meilleur score)
    if (query.next()) {
        QString bestScore = query.value("Meilleur_Score").toString();
        return bestScore;
    } else {
        qDebug() << "Aucun meilleur score trouvé pour l'utilisateur : " << userName << " et le jeu : " << title;
            return "No highscore found";
    }

    // Fermer la connexion à la base de données (à placer dans une méthode séparée)
    db.close();
}
