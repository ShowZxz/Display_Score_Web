# Display_Score_Web

QList<ScoreInfoTop> Sgdb::getWorldScoreInfo(const QString &title) {
    QList<ScoreInfoTop> worldScore;

    // Vérifier la connexion à la base de données
    if (!db.open()) {
        qDebug() << "Impossible d'ouvrir la base de données. getWorldScoreInfo";
            return worldScore;
    } else {
        qDebug() << "Ouverture de la base de données SDGB.";
    }

    // Préparer la requête SQL avec des paramètres liés
    QSqlQuery query;
    query.prepare("SELECT u.Pseudo AS User, s.Score "
                  "FROM scores s "
                  "JOIN utilisateurs u ON s.ID_utilisateur = u.ID_utilisateur "
                  "JOIN jeux j ON s.ID_jeu = j.ID_jeu "
                  "WHERE j.Nom_jeu = :title AND s.Score = (SELECT MAX(s2.Score) "
                  "FROM scores s2 "
                  "JOIN jeux j2 ON s2.ID_jeu = j2.ID_jeu "
                  "WHERE j2.Nom_jeu = :title2);");
    query.bindValue(":title", title);
    query.bindValue(":title2", title);

    // Exécuter la requête
    if (!query.exec()) {
        qDebug() << query.lastError() << "Erreur lors de l'exécution de la requête getWorldScoreInfo";
        return worldScore;
    }

    // Récupérer les résultats
    while (query.next()) {
        ScoreInfoTop scoreInfo;
        scoreInfo.username = query.value("User").toString();
        scoreInfo.score = query.value("Score").toLongLong();
        worldScore.append(scoreInfo);
    }

    // Fermer la connexion à la base de données (à placer dans une méthode séparée)
    db.close();

    return worldScore;
}

QString Sgdb::getNewHighscore(QString userName, QString title) {
    // Vérifier la connexion à la base de données
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


QString Sgdb::getInfoRank(const QString &user, const QString &title){
    QString result;
        if (!db.open()) {
                    qDebug() << "Erreur lors de l'ouverture de la base de données";
                    return result;
                }

                // Obtenir le meilleur score de l'utilisateur pour le titre donné
                QString queryStr = QString("SELECT s.Score AS Score,(SELECT COUNT(*) FROM scores s2 JOIN utilisateurs u ON s2.ID_utilisateur = u.ID_utilisateur JOIN jeux j ON s2.ID_jeu = j.ID_jeu WHERE j.Nom_jeu = '%1' AND s2.Score > s.Score) + 1 AS Rank FROM scores s JOIN utilisateurs u ON s.ID_utilisateur = u.ID_utilisateur JOIN jeux j ON s.ID_jeu = j.ID_jeu WHERE j.Nom_jeu = '%1' AND u.Pseudo = '%2';").arg(title).arg(user);


                QSqlQuery query;
                if (!query.exec(queryStr)) {
                    qDebug() << "Erreur lors de l'exécution de la requête : " << query.lastError();

                    return result;
                }

                if (query.next()) {
                    QString score = query.value("score").toString();
                    int rank = query.value("Rank").toInt();

                    result = "Meilleur score de l'utilisateur " + user + " pour le titre " + title + " : " + score +
                             "\nClassement : #" + QString::number(rank);
                }

                // Fermer la connexion à la base de données

                db.close();
                return result;
}



void Sgdb::closeDatabase(){

    db.close();
    qDebug() << "Connexion à la base de données fermée";

}



QList<ScoreInfoTop> Sgdb::getTopScoresBehindUser(const QString &title, const QString &user){
    QList<ScoreInfoTop> behindScores;


    if (!db.open()) {
            qDebug() << "Erreur de connexion à la base de données getTopScoresBehindUser";
            return behindScores;
    }

    QString queryStr = QString("SELECT u.Pseudo AS User, s.Score "
                               "FROM scores s "
                               "JOIN utilisateurs u ON s.ID_utilisateur = u.ID_utilisateur "
                               "JOIN jeux j ON s.ID_jeu = j.ID_jeu "
                               "WHERE j.Nom_jeu = '%1' AND s.Score < (SELECT s2.Score "
                               "FROM scores s2 "
                               "JOIN utilisateurs u2 ON s2.ID_utilisateur = u2.ID_utilisateur "
                               "JOIN jeux j2 ON s2.ID_jeu = j2.ID_jeu "
                               "WHERE j2.Nom_jeu = '%1' AND u2.Pseudo = '%2') "
                               "ORDER BY s.Score DESC "
                               "LIMIT 1;").arg(title).arg(user);

    QSqlQuery query(queryStr);

    if (!query.exec()) {
            qDebug() << "Erreur lors de l'exécution de la requête getTopScoresBehindUser " << query.lastError();

            return behindScores;
    }

    if (query.next()) {
            ScoreInfoTop scoreInfo;
            scoreInfo.username = query.value(0).toString();
            scoreInfo.score = query.value(1).toLongLong();
            behindScores.append(scoreInfo);
    }

    db.close();
    return behindScores;


}


QList<ScoreInfoTop> Sgdb::getTopScoresInFronUser(const QString &title, const QString &user) {
    QList<ScoreInfoTop> topScores;


    if (!db.open()) {
        qDebug() << "Erreur de connexion à la base de données getTopScoresInFronUse";
        return topScores;
    }

    QString queryStr = QString("SELECT u.Pseudo AS User, s.Score "
        "FROM scores s "
        "JOIN utilisateurs u ON s.ID_utilisateur = u.ID_utilisateur "
        "JOIN jeux j ON s.ID_jeu = j.ID_jeu "
        "WHERE j.Nom_jeu = '%1' AND u.Pseudo != '%2' "
        "ORDER BY s.Score ASC "
        "LIMIT 1;").arg(title).arg(user);
    QSqlQuery query(queryStr);

    if (!query.exec()) {
        qDebug() << query.lastError() << " Erreur lors de l'exécution de la requête getTopScoresInFronUser ";

        return topScores;
    }

    if (query.next()) {
        ScoreInfoTop scoreInfo;
        scoreInfo.username = query.value(0).toString();
        scoreInfo.score = query.value(1).toLongLong();
        topScores.append(scoreInfo);
    }

    db.close();
    return topScores;
}
