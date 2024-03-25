# Display_Score_Web
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
