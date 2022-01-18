<?php

namespace Drupal\form_examples\Form;

use Drupal\Core\Form\FormBase;
use \Drupal\Core\Form\FormStateInterface;

class KitchenSinkForm extends FormBase {
  
  public function getFormId() {
    // Unique ID of the form
    return 'form_examples_kitchen_sink';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your Name'),
      '#default_value' => $this->currentUser()->getDisplayName(),
      '#weight' => 20,
    ];

    $form['group_one'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Group One'),
    ];

    $departments = [
      ''        => $this->t('--Select a Department--'),
      'service' => $this->t('Customer Service'),
      'hr'      => $this->t('Human Resources'),
      'it'      => $this->t('Information Technology'),
      'sales'   => $this->t('Sales'),
    ];
    $form['group_one']['department'] = [
      '#type' => 'select',
      '#title' => $this->t('Department'),
      '#options' => $departments,
      '#default_value' => 'it',
      '#description' => $this->t('Please select a department.'),
    ];

    $form['group_one']['department_multi'] = [
      '#type' => 'select',
      '#title' => $this->t('Departments (Multiselect)'),
      '#options' => $departments,
      '#multiple' => TRUE,
      '#default_value' => ['it', 'service'],
    ];

    $intervals = [
      'hourly'  => $this->t('Hourly'),
      'daily'   => $this->t('Daily'),
      'weekly'  => $this->t('Weekly'),
      'monthly' => $this->t('Monthly'),
    ];

    $form['group_one']['receive_updates'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Receive Updates'),
      '#options' => $intervals,
      '#default_value' => ['daily'],
    ];

    $form['group_two'] = [
      '#type' => 'details',
      '#title' => $this->t('Group Two'),
      '#open' => TRUE,
    ];

    $form['group_two']['birthdate'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Birth date'),
      '#default_value' => \Drupal\Core\Datetime\DrupalDateTime::createFromArray(
          [
            'year' => 2017,
            'month' => 04,
            'day' => 03,
          ]
      ),
      '#date_time_element' => 'none',
    ];

    $form['group_two']['subscribe'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Subscribe to our newsletter'),
      '#default_value' => TRUE,
    ];

    $form['group_two']['occupation'] = [
      '#type' => 'radios',
      '#title' => $this->t('Occupation'),
      '#options' => [
        'teacher' => $this->t('Teacher'), 
        'lawyer' => $this->t('Lawyer'),
        'programmer' => $this->t('Programmer'),
        'other' => $this->t('Other'),
      ],
      '#states' => [
        // Hide the occupations when the subscribe checkbox is disabled.
        'invisible' => [
          ':input[name="subscribe"]' => ['checked' => FALSE],
        ],
       ],
    ];

    $form['group_two']['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#rows' => 5,
      '#description' => $this->t('Please enter a message here for us to read.'),
      '#states' => [
        'visible' => [
          ':input[name="subscribe"]' => ['checked' => TRUE],
          ':input[name="occupation"]' => ['value' => 'other'],
        ],
        'required' => [
          ':input[name="occupation"]' => ['value' => 'other'],
        ],
       ],
    ];

    $form['group_three'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Group Three'),
    ];

    $form['group_three']['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#maxlength' => 10,
      '#size' => 20,
      '#attributes' => [
        'class' => [
          'kitchen-sink-highlight', 
          'some-other-class',
        ]
      ],
      '#description' => $this->t('Your username can be a maximum of 10 characters.'),
    ];

    $form['group_three']['password'] = [
      '#type' => 'password',
      '#title' => $this->t('Simple Password'),
      '#description' => $this->t('Please enter a password'),
    ];

    $form['group_three']['password_two'] = [
      '#type' => 'password_confirm',
      '#title' => $this->t('Password (with confirm)'),
    ];

    $form['group_four'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Group Four'),
    ];

    $form['group_four']['total'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Total'),
      '#field_prefix' => '$',
      '#size' => 10,
    ];

    $form['group_four']['amount'] = [
      '#type' => 'number',
      '#title' => $this->t('Amount'),
      '#field_suffix' => 'grams',
      '#size' => 10,
      '#weight' => -1,
    ];

    $form['group_four']['order_notes'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Order Notes'),
      '#prefix' => '<p>' . $this->t('Enter any order notes below.') . '</p>',
      '#suffix' => '<p>' . $this->t('Enter any order notes above.') . '</p>',
    ];

    $form['group_five'] = [
      '#type' => 'container',
      '#tree' => TRUE,
    ];

    $form['group_five']['disabled_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Disabled Text'),
      '#default_value' => $this->t('This is some default text.'),
      '#disabled' => TRUE,
    ];

    $form['group_five']['readonly_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Read-only Text'),
      '#default_value' => $this->t('This is some default text.'),
      '#attributes' => [
        'readonly' => 'readonly', 
        'class' => [
          'kitchen-sink-highlight', 
          'some-other-class'
        ]
      ],
    ];

    $form['group_five']['secret_value'] = [
      '#type' => 'hidden',
      '#value' => 'the secret password',
    ];

    $form['group_five']['supersecret_value'] = [
      '#type' => 'value',
      '#value' => 'the supersecret password',
    ];

    $form['group_five']['topsecret_value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Top Secret Password'),
      '#default_value' => 'the top secret password',
      '#access' => ($this->currentUser()->id() == 1),
    ];

    $form['finish_line'] = [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => $this->t('Congratulations! You have made it to the end of the form.'),
    ];


    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit Form'),
      '#name' => 'firstsubmit',
    ];

    $form['othersubmit'] = [
      '#type' => 'submit',
      '#value' => $this->t('No, use me!'),
      '#name' => 'othersubmit',
    ];

    $form['cancel'] = [
      '#type' => 'link',
      '#title' => $this->t('Cancel'),
      '#url' => \Drupal\Core\Url::fromRoute('<front>'),
    ];
  
    $form['#attached']['library'][] = 'form_examples/kitchen-sink';
    
    return $form;
  }

  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::messenger()->addMessage($this->t('Thanks for submitting the form!'));
    ksm($form_state->getValues());
    ksm($form_state->getTriggeringElement());
  }

}
