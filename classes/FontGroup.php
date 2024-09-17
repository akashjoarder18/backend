<?php
class FontGroup
{
    private $groups = [];
    private $groups_delete = [];
    public function __construct()
    {
        if (file_exists(__DIR__ . '/font_groups.json')) {
            $this->groups = json_decode(file_get_contents(__DIR__ . '/font_groups.json'), true);
        }
    }

    public function createGroup($fontG, $fonts)
    {

        if (is_countable($fonts) && count($fonts) < 2) {
            return ['error' => 'At least two fonts are required to create a group'];
        }
        $count = explode(',', $fonts);

        $newGroup = [
            'id' => uniqid(),
            'group' => $fontG,
            'fonts' => trim($fonts, '[]'),
            'count' => count($count)
        ];

        $this->groups[] = $newGroup;
        $this->saveGroups();
        return ['success' => true, 'group' => $newGroup];
    }

    public function getAllGroups()
    {

        return $this->groups;
    }

    public function editGroup($id, $fonts)
    {
        foreach ($this->groups as &$group) {
            if ($group['id'] == $id) {
                $group['fonts'] = $fonts;
                $this->saveGroups();
                return ['success' => true, 'group' => $group];
            }
        }

        return ['error' => 'Group not found'];
    }

    public function deleteGroup($id)
    {
        $this->groups = array_filter($this->groups, function ($group) use ($id) {
            return $group['id'] !== $id;
        });

         foreach($this->groups as $row){
            array_push($this->groups_delete,$row);
         }

        $this->groups = [];
        $this->groups =  $this->groups_delete;
        $this->saveGroups();
        
        return ['success' => true];
    }

    private function saveGroups()
    {
       file_put_contents(__DIR__ . '/font_groups.json', json_encode($this->groups));
    }
}
