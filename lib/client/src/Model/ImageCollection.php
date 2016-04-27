<?php
/**
 * ImageCollection
 *
 * PHP version 5
 *
 * @category Class
 * @package  DBCDK\CommunityServices
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */
/**
 *  Copyright 2016 SmartBear Software
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace DBCDK\CommunityServices\Model;

use \ArrayAccess;
/**
 * ImageCollection Class Doc Comment
 *
 * @category    Class
 * @description 
 * @package     DBCDK\CommunityServices
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class ImageCollection implements ArrayAccess
{
    /**
      * Array of property to type mappings. Used for (de)serialization 
      * @var string[]
      */
    static $swaggerTypes = array(
        'id' => 'double',
        'profileImageCollection' => 'double',
        'groupCoverImageCollectionId' => 'double',
        'postImageCollection' => 'double',
        'commentImageCollection' => 'double',
        'reviewImageCollection' => 'double'
    );
  
    static function swaggerTypes() {
        return self::$swaggerTypes;
    }

    /** 
      * Array of attributes where the key is the local name, and the value is the original name
      * @var string[] 
      */
    static $attributeMap = array(
        'id' => 'id',
        'profileImageCollection' => 'profileImageCollection',
        'groupCoverImageCollectionId' => 'groupCoverImageCollectionId',
        'postImageCollection' => 'postImageCollection',
        'commentImageCollection' => 'commentImageCollection',
        'reviewImageCollection' => 'reviewImageCollection'
    );
  
    static function attributeMap() {
        return self::$attributeMap;
    }

    /**
      * Array of attributes to setter functions (for deserialization of responses)
      * @var string[]
      */
    static $setters = array(
        'id' => 'setId',
        'profileImageCollection' => 'setProfileImageCollection',
        'groupCoverImageCollectionId' => 'setGroupCoverImageCollectionId',
        'postImageCollection' => 'setPostImageCollection',
        'commentImageCollection' => 'setCommentImageCollection',
        'reviewImageCollection' => 'setReviewImageCollection'
    );
  
    static function setters() {
        return self::$setters;
    }

    /**
      * Array of attributes to getter functions (for serialization of requests)
      * @var string[]
      */
    static $getters = array(
        'id' => 'getId',
        'profileImageCollection' => 'getProfileImageCollection',
        'groupCoverImageCollectionId' => 'getGroupCoverImageCollectionId',
        'postImageCollection' => 'getPostImageCollection',
        'commentImageCollection' => 'getCommentImageCollection',
        'reviewImageCollection' => 'getReviewImageCollection'
    );
  
    static function getters() {
        return self::$getters;
    }

    
    /**
      * $id 
      * @var double
      */
    protected $id;
    
    /**
      * $profileImageCollection 
      * @var double
      */
    protected $profileImageCollection;
    
    /**
      * $groupCoverImageCollectionId 
      * @var double
      */
    protected $groupCoverImageCollectionId;
    
    /**
      * $postImageCollection 
      * @var double
      */
    protected $postImageCollection;
    
    /**
      * $commentImageCollection 
      * @var double
      */
    protected $commentImageCollection;
    
    /**
      * $reviewImageCollection 
      * @var double
      */
    protected $reviewImageCollection;
    

    /**
     * Constructor
     * @param mixed[] $data Associated array of property value initalizing the model
     */
    public function __construct(array $data = null)
    {
        
        if ($data != null) {
            $this->id = $data["id"];
            $this->profileImageCollection = $data["profileImageCollection"];
            $this->groupCoverImageCollectionId = $data["groupCoverImageCollectionId"];
            $this->postImageCollection = $data["postImageCollection"];
            $this->commentImageCollection = $data["commentImageCollection"];
            $this->reviewImageCollection = $data["reviewImageCollection"];
        }
    }
    
    /**
     * Gets id
     * @return double
     */
    public function getId()
    {
        return $this->id;
    }
  
    /**
     * Sets id
     * @param double $id 
     * @return $this
     */
    public function setId($id)
    {
        
        $this->id = $id;
        return $this;
    }
    
    /**
     * Gets profileImageCollection
     * @return double
     */
    public function getProfileImageCollection()
    {
        return $this->profileImageCollection;
    }
  
    /**
     * Sets profileImageCollection
     * @param double $profileImageCollection 
     * @return $this
     */
    public function setProfileImageCollection($profileImageCollection)
    {
        
        $this->profileImageCollection = $profileImageCollection;
        return $this;
    }
    
    /**
     * Gets groupCoverImageCollectionId
     * @return double
     */
    public function getGroupCoverImageCollectionId()
    {
        return $this->groupCoverImageCollectionId;
    }
  
    /**
     * Sets groupCoverImageCollectionId
     * @param double $groupCoverImageCollectionId 
     * @return $this
     */
    public function setGroupCoverImageCollectionId($groupCoverImageCollectionId)
    {
        
        $this->groupCoverImageCollectionId = $groupCoverImageCollectionId;
        return $this;
    }
    
    /**
     * Gets postImageCollection
     * @return double
     */
    public function getPostImageCollection()
    {
        return $this->postImageCollection;
    }
  
    /**
     * Sets postImageCollection
     * @param double $postImageCollection 
     * @return $this
     */
    public function setPostImageCollection($postImageCollection)
    {
        
        $this->postImageCollection = $postImageCollection;
        return $this;
    }
    
    /**
     * Gets commentImageCollection
     * @return double
     */
    public function getCommentImageCollection()
    {
        return $this->commentImageCollection;
    }
  
    /**
     * Sets commentImageCollection
     * @param double $commentImageCollection 
     * @return $this
     */
    public function setCommentImageCollection($commentImageCollection)
    {
        
        $this->commentImageCollection = $commentImageCollection;
        return $this;
    }
    
    /**
     * Gets reviewImageCollection
     * @return double
     */
    public function getReviewImageCollection()
    {
        return $this->reviewImageCollection;
    }
  
    /**
     * Sets reviewImageCollection
     * @param double $reviewImageCollection 
     * @return $this
     */
    public function setReviewImageCollection($reviewImageCollection)
    {
        
        $this->reviewImageCollection = $reviewImageCollection;
        return $this;
    }
    
    /**
     * Returns true if offset exists. False otherwise.
     * @param  integer $offset Offset 
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }
  
    /**
     * Gets offset.
     * @param  integer $offset Offset 
     * @return mixed 
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }
  
    /**
     * Sets value based on offset.
     * @param  integer $offset Offset 
     * @param  mixed   $value  Value to be set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }
  
    /**
     * Unsets offset.
     * @param  integer $offset Offset 
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }
  
    /**
     * Gets the string presentation of the object
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) {
            return json_encode(\DBCDK\CommunityServices\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
        } else {
            return json_encode(\DBCDK\CommunityServices\ObjectSerializer::sanitizeForSerialization($this));
        }
    }
}
