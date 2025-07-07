<?php
require_once 'db.php';

class Community {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ==================== EVENTS METHODS ====================
    
    public function getAllEvents($limit = null, $category = null, $status = 'upcoming') {
        $sql = "SELECT * FROM community_events WHERE status = :status";
        $params = ['status' => $status];
        
        if ($category) {
            $sql .= " AND category = :category";
            $params['category'] = $category;
        }
        
        $sql .= " ORDER BY event_date ASC, start_time ASC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
            $params['limit'] = $limit;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getEventById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM community_events WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function createEvent($data) {
        $sql = "INSERT INTO community_events (title, description, event_date, start_time, end_time, location, category, tags, max_participants, image, featured) 
                VALUES (:title, :description, :event_date, :start_time, :end_time, :location, :category, :tags, :max_participants, :image, :featured)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'event_date' => $data['event_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'location' => $data['location'],
            'category' => $data['category'],
            'tags' => json_encode($data['tags']),
            'max_participants' => $data['max_participants'],
            'image' => $data['image'] ?? null,
            'featured' => $data['featured'] ?? 0
        ]);
    }

    public function updateEvent($id, $data) {
        $sql = "UPDATE community_events SET 
                title = :title, description = :description, event_date = :event_date, 
                start_time = :start_time, end_time = :end_time, location = :location, 
                category = :category, tags = :tags, max_participants = :max_participants, 
                image = :image, featured = :featured, status = :status 
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'description' => $data['description'],
            'event_date' => $data['event_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'location' => $data['location'],
            'category' => $data['category'],
            'tags' => json_encode($data['tags']),
            'max_participants' => $data['max_participants'],
            'image' => $data['image'] ?? null,
            'featured' => $data['featured'] ?? 0,
            'status' => $data['status'] ?? 'upcoming'
        ]);
    }

    public function deleteEvent($id) {
        $stmt = $this->pdo->prepare("DELETE FROM community_events WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getEventParticipants($eventId) {
        $sql = "SELECT ep.*, u.username, u.full_name 
                FROM event_participants ep 
                JOIN users u ON ep.user_id = u.id 
                WHERE ep.event_id = :event_id 
                ORDER BY ep.registered_at ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['event_id' => $eventId]);
        return $stmt->fetchAll();
    }

    // ==================== GROUPS METHODS ====================
    
    public function getAllGroups($limit = null, $category = null) {
        $sql = "SELECT * FROM community_groups WHERE status = 'active'";
        $params = [];
        
        if ($category) {
            $sql .= " AND category = :category";
            $params['category'] = $category;
        }
        
        $sql .= " ORDER BY member_count DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
            $params['limit'] = $limit;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getGroupById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM community_groups WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function createGroup($data) {
        $sql = "INSERT INTO community_groups (name, description, category, icon, max_members, meeting_frequency, tags) 
                VALUES (:name, :description, :category, :icon, :max_members, :meeting_frequency, :tags)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'category' => $data['category'],
            'icon' => $data['icon'],
            'max_members' => $data['max_members'],
            'meeting_frequency' => $data['meeting_frequency'],
            'tags' => json_encode($data['tags'])
        ]);
    }

    public function updateGroup($id, $data) {
        $sql = "UPDATE community_groups SET 
                name = :name, description = :description, category = :category, 
                icon = :icon, max_members = :max_members, meeting_frequency = :meeting_frequency, 
                tags = :tags, status = :status 
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'],
            'category' => $data['category'],
            'icon' => $data['icon'],
            'max_members' => $data['max_members'],
            'meeting_frequency' => $data['meeting_frequency'],
            'tags' => json_encode($data['tags']),
            'status' => $data['status'] ?? 'active'
        ]);
    }

    public function deleteGroup($id) {
        $stmt = $this->pdo->prepare("DELETE FROM community_groups WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getGroupMembers($groupId) {
        $sql = "SELECT gm.*, u.username, u.full_name 
                FROM group_members gm 
                JOIN users u ON gm.user_id = u.id 
                WHERE gm.group_id = :group_id 
                ORDER BY gm.joined_at ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['group_id' => $groupId]);
        return $stmt->fetchAll();
    }

    // ==================== FORUM METHODS ====================
    
    public function getAllForumCategories() {
        $stmt = $this->pdo->prepare("SELECT * FROM forum_categories WHERE status = 'active' ORDER BY order_number ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getForumCategoryById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM forum_categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function createForumCategory($data) {
        $sql = "INSERT INTO forum_categories (name, description, icon, order_number) 
                VALUES (:name, :description, :icon, :order_number)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'icon' => $data['icon'],
            'order_number' => $data['order_number'] ?? 0
        ]);
    }

    public function updateForumCategory($id, $data) {
        $sql = "UPDATE forum_categories SET 
                name = :name, description = :description, icon = :icon, 
                order_number = :order_number, status = :status 
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'],
            'icon' => $data['icon'],
            'order_number' => $data['order_number'] ?? 0,
            'status' => $data['status'] ?? 'active'
        ]);
    }

    public function deleteForumCategory($id) {
        $stmt = $this->pdo->prepare("DELETE FROM forum_categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getTopicsByCategory($categoryId, $limit = null) {
        $sql = "SELECT ft.*, u.username, u.full_name 
                FROM forum_topics ft 
                JOIN users u ON ft.user_id = u.id 
                WHERE ft.category_id = :category_id AND ft.status = 'active' 
                ORDER BY ft.pinned DESC, ft.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['category_id' => $categoryId]);
        return $stmt->fetchAll();
    }

    public function createTopic($data) {
        $sql = "INSERT INTO forum_topics (category_id, user_id, title, content) 
                VALUES (:category_id, :user_id, :title, :content)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'category_id' => $data['category_id'],
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'content' => $data['content']
        ]);
    }

    public function getTopicById($id) {
        $sql = "SELECT ft.*, u.username, u.full_name, fc.name as category_name 
                FROM forum_topics ft 
                JOIN users u ON ft.user_id = u.id 
                JOIN forum_categories fc ON ft.category_id = fc.id 
                WHERE ft.id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getTopicReplies($topicId) {
        $sql = "SELECT fr.*, u.username, u.full_name 
                FROM forum_replies fr 
                JOIN users u ON fr.user_id = u.id 
                WHERE fr.topic_id = :topic_id AND fr.status = 'active' 
                ORDER BY fr.created_at ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['topic_id' => $topicId]);
        return $stmt->fetchAll();
    }

    public function createReply($data) {
        $sql = "INSERT INTO forum_replies (topic_id, user_id, content) 
                VALUES (:topic_id, :user_id, :content)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'topic_id' => $data['topic_id'],
            'user_id' => $data['user_id'],
            'content' => $data['content']
        ]);
    }

    // ==================== STATISTICS METHODS ====================
    
    public function getCommunityStats() {
        $stats = [];
        
        // Total events
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM community_events WHERE status = 'upcoming'");
        $stats['total_events'] = $stmt->fetch()['total'];
        
        // Total groups
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM community_groups WHERE status = 'active'");
        $stats['total_groups'] = $stmt->fetch()['total'];
        
        // Total forum categories
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM forum_categories WHERE status = 'active'");
        $stats['total_categories'] = $stmt->fetch()['total'];
        
        // Total members (users)
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
        $stats['total_members'] = $stmt->fetch()['total'];
        
        return $stats;
    }

    public function getEventCategories() {
        return [
            'language_exchange' => '言語交換',
            'cultural_experience' => '文化体験',
            'sports' => 'スポーツ',
            'social' => '交流',
            'other' => 'その他'
        ];
    }

    public function getGroupCategories() {
        return [
            'language_exchange' => '言語交換',
            'cultural_activities' => '文化活動',
            'sports' => 'スポーツ',
            'hobbies' => '趣味',
            'study' => '勉強',
            'other' => 'その他'
        ];
    }
}
?> 