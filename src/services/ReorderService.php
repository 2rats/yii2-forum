<?php

namespace rats\forum\services;

use Yii;
use yii\base\Exception;

class ReorderService
{
    /**
     * Reorder items and their related child items.
     * 
     * @param array $data The data containing items to be reordered.
     * @param string $parentModelClass The class name of the parent model.
     * @param string $childModelClass The class name of the child model.
     * @param string $childForeignKey The foreign key attribute name in the child model.
     * @param string $parentOrderField The ordering field name in the parent model.
     * @param string $childOrderField The ordering field name in the child model.
     * @return array The result of the reordering process.
     * @throws DbException If a database error occurs.
     */
    public function reorderItems(
        array $data,
        string $parentModelClass,
        string $childModelClass,
        string $childForeignKey,
        string $parentOrderField = 'ord',
        string $childOrderField = 'ord'
    ) {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (empty($data)) {
                throw new Exception('No data provided for reordering.');
            }


            foreach ($data as $order => $obj) {
                $parent = $parentModelClass::findOne($obj['parentItem']);
                if ($parent === null) {
                    throw new Exception('Invalid parent item ID: ' . $obj['parentItem']);
                }

                $parent->$parentOrderField = $order + 1;
                if (!$parent->save(false, [$parentOrderField])) {
                    throw new Exception('Failed to save parent: ' . $parent->id);
                }

                foreach ($obj['childItems'] as $childOrder => $childId) {
                    $child = $childModelClass::findOne($childId);
                    if ($child === null) {
                        throw new Exception('Invalid child ID: ' . $childId);
                    }

                    $child->$childOrderField = $childOrder + 1;
                    $child->$childForeignKey = $parent->id;
                    if (!$child->save(false, [$childOrderField, $childForeignKey])) {
                        throw new Exception('Failed to save child: ' . $child->id);
                    }
                }
            }

            $transaction->commit();
            return ['success' => true, 'message' => 'Reordering completed successfully.'];

        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}

