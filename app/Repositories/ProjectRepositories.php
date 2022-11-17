<?php

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\BaseRepository;

/**
 * Class WorkRepository
 * @package App\Repositories
 * @version April 6, 2018, 10:39 pm UTC
 *
 * @method Work findWithoutFail($id, $columns = ['*'])
 * @method Work find($id, $columns = ['*'])
 * @method Work first($columns = ['*'])
*/
class ProjectRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'creation_date',
        'last_modification_date',
        'archived',
        'coordinator'
        
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Project::class;
    }
}

