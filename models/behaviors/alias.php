<?php
/**
 * The alias behavior allows you to use column aliases to refer to a particular
 * field in your model using another name.
 *
 * The behavior will figure out in your find/save operations which fields
 * correspond to which aliases and vice-versa.
 *
 * For find operations, string-based conditions are not supported. Only array
 * based conditions will work.
 *
 * Interestingly, findBy* and findAllBy* methods work for aliases :)
 *
 * @author Matthew Harris <shugotenshi@gmail.com>
 */
class AliasBehavior extends ModelBehavior {
	/**
	 * Field aliases.
	 *
	 * @var array
	 * @access private
	 */
	var $__aliases = array();

	/**
	 * Setup aliases.
	 *
	 * @param Model model
	 * @param array $config
	 */
	function setup(&$model, $config = array()) {
		if (is_array($config)) {
			foreach ($config as $field => $aliases) {
				if (!is_array($aliases)) {
					$aliases = array($aliases);
				}
				if ($model->hasField($field)) {
					$this->__aliases[$field] = $aliases;
				}
			}
		}
	}

	/**
	 * Get the field->alias mapping.
	 *
	 * @return array
	 * @access public
	 */
	function getAliases()
	{
		return $this->__aliases;
	}

	/**
	 * Replace field values with the actual values in their aliases.
	 * This only works when an array is used for conditions, instead of a
	 * string partial.
	 *
	 * @param Model $model
	 * @param array $queryData
	 * @return array
	 * @access public
	 */
	function beforeFind(&$model, $queryData) {
		if (isset($queryData['conditions']) && is_array($queryData['conditions'])) {
			foreach ($this->__aliases as $field => $aliases) {
				foreach ($aliases as $alias) {
					if (isset($queryData['conditions'][$alias])) {
						$queryData['conditions'][$model->alias.'.'.$field] = $queryData['conditions'][$alias];
						unset($queryData['conditions'][$alias]);
					}

					if (isset($queryData['conditions'][$model->alias.'.'.$alias])) {
						$queryData['conditions'][$model->alias.'.'.$field] = $queryData['conditions'][$model->alias.'.'.$alias];
						unset($queryData['conditions'][$model->alias.'.'.$alias]);
					}
				}
			}
		}
		return $queryData;
	}

	/**
	 * Replace field values with the value stored in their alias fields.
	 * The actual value will be the one stored in the last alias for a given
	 * field.
	 *
	 * @param Model $model
	 * @return boolean
	 * @access public
	 */
	function beforeSave(&$model) {
		if (isset($model->data[$model->alias]) && is_array($model->data[$model->alias])) {
			foreach ($this->__aliases as $field => $aliases) {
				foreach ($aliases as $alias) {
					if (isset($model->data[$model->alias][$alias])) {
						$model->data[$model->alias][$field] = $model->data[$model->alias][$alias];
						unset($model->data[$model->alias][$alias]);
					}
				}
			}
		}
		return true;
	}

	/**
	 * Set aliases to the value of corresponding field.
	 *
	 * @param Model $model
	 * @param array $results
	 * @return array
	 * @access public
	 */
	function afterFind(&$model, $results) {
		foreach ($results as $key => $result) {
			if (isset($results[$key][$model->alias]) && is_array($results[$key][$model->alias])) {
				foreach ($this->__aliases as $field => $aliases) {
					if (isset($results[$key][$model->alias][$field])) {
						foreach ($aliases as $alias) {
							$results[$key][$model->alias][$alias] = $results[$key][$model->alias][$field];
						}
					}
				}
			}
		}
		return $results;
	}

	/**
	 * Set aliases to the value of corresponding field.
	 *
	 * @param Model $model
	 * @return boolean
	 */
	function afterSave(&$model) {
		if (isset($model->data[$model->alias]) && is_array($model->data[$model->alias])) {
			foreach ($this->__aliases as $field => $aliases) {
				if (isset($model->data[$model->alias][$field])) {
					foreach ($aliases as $alias) {
						$model->data[$model->alias][$alias] = $model->data[$model->alias][$field];
					}
				}
			}
		}
		return true;
	}
}
?>