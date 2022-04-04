<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of article
 *
 * @author Administrator
 */
class article extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_article';
    public $primaryKey = 'article_id';

    public function get_article_old($doc_lang, $condition = array('article.article_id < ' => 9000, 'display_flag' => 'Y')) {
        if ($doc_lang) {
            //$article = $this->find('list', array('fields' => array('article.article_id', 'article.article_desc_'.$doc_lang), 'conditions' => $condition, 'order' => array('article.display_order' => 'ASC')));
            //$article = $this->find('list', array('fields' => array('article.article_id', 'article.article_desc_'.$doc_lang), 'conditions' => $condition, 'order' => array('article.display_order' => 'ASC')));
            $article = $this->find('all', array('fields' => array('article.article_id', 'article.article_desc_en', 'article_desc_ll', 'article_ll_activation_flag'), 'conditions' => $condition, 'order' => array('article.display_order' => 'ASC')));
        } else {
            $article = $this->find('list', array('fields' => array('article.article_id', 'article.article_desc_en'), 'conditions' => $condition, 'order' => array('article.display_order' => 'ASC')));
        }
        return $article;
    }
    
    
      public function get_article($doc_lang,$condition = array('article.article_id < ' => 9000,'display_flag'=>'Y')) {
       if($doc_lang)
       {
           $article = $this->find('list', array('fields' => array('article.article_id', 'article.article_desc_'.$doc_lang), 'conditions' => $condition, 'order' => array('article.article_desc_en' => 'ASC')));
 
       }
       else{
        $article = $this->find('list', array('fields' => array('article.article_id', 'article.article_desc_en'), 'conditions' => $condition, 'order' => array('article.article_desc_en' => 'ASC')));
       }
        return $article;
    }

    public function get_article_hp($doc_lang, $condition = array('article.article_id < ' => 9000, 'display_flag' => 'Y')) {
        if ($doc_lang) {
            $article = $this->find('list', array('fields' => array('article.article_id', 'article.article_desc_' . $doc_lang), 'conditions' => $condition, 'order' => array('display_order' => 'ASC')));
        } else {
            $article = $this->find('list', array('fields' => array('article.article_id', 'article.article_desc_en'), 'conditions' => $condition, 'order' => array('display_order' => 'ASC')));
        }
        return $article;
    }

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_article';
        $duplicate['PrimaryKey'] = 'article_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'article_desc_' . $language['mainlanguage']['language_code']);
        }
        array_push($fields, 'article_code');

        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        $fieldlist = array();
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['article_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphanumspacecommaroundbrackets';
            } else {
                $fieldlist['article_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];
                ;
            }
        }
        $fieldlist['article_code']['text'] = 'is_required,is_alphaspacedashdotcommacolonroundbrackets';
        $fieldlist['display_order']['text'] = 'is_required,is_numeric';
        $fieldlist['book_number']['text'] = 'is_required,is_alphanumeric';
        return $fieldlist;
    }

}
