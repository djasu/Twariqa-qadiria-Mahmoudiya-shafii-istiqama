// Dans le tableau des activités
<td>
    <a href="registrations.php?activity_id=<?= $activity['id'] ?>" class="btn btn-sm btn-info">
        Voir inscriptions (<?= getRegistrationCount($activity['id']) ?>)
    </a>
</td>

<?php
function getRegistrationCount($activityId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM activity_registrations WHERE activity_id = ?");
    $stmt->execute([$activityId]);
    return $stmt->fetchColumn();
}
?>