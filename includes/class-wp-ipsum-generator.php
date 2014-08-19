<?php

/**
 * Class SpinPress_WP_Ipsum_Generator.
 *
 * Inspired by the Bacon Ipsum generator.
 *
 * @see https://github.com/petenelson/bacon-ipsum
 *
 */
class SpinPress_WP_Ipsum_Generator {

	/**
	 * @var array
	 */
	protected $options = array();

	/**
	 * Class Constructor.
	 *
	 * @param mixed $args An array or string containing the arguments.
	 */
	public function __construct( $args ) {

		$defaults = array(
			'use_filler_words' => false,
			'paragraphs' => 5,
			'start_with_lorem' => true,
		);

		$this->options = wp_parse_args( $args, $defaults );
	}

	protected function get_words() {

		$wpwords = array(
			// Release Names
			'WordPress',
			'Jazz',
			'Davis',
			'Blakey',
			'Mingus',
			'Strayhorn',
			'Duke',
			'Boren',
			'Ella',
			'Getz',
			'Dexter',
			'Brecker',
			'Tyner',
			'Coltrane',
			'Baker',
			'Carmen',
			'Monk',
			'Reinhardt',
			'Gershwin',
			'Stitt',
			'Green',
			'Jones',
			'Oscar',
			'Basie',
			'Parker',
			'Smith',

			// People
			'Matt',
			'Mullenweg',
			'Mark',
			'Jaquith',
			'Ozz',
			'Andrew',
			'Nacin',
			'Otto',
			'Westi',
			'Helen',
			'Jen',
			'Andrea',
			'Donncha',
			'Mike',

			// Companies etc.
			'Automattic',
			'Foundation',
			'WordCamp',
			'Meetup',

			// Components
			'Post',
			'Theme',
			'Plugin',
			'Filter',
			'Action',
			'Multisite',
			'Transient',

			// General
			'Release',
			'Update',
			'Upgrade',
			'Language',
			'Install',
			'Chat',
			'Premium',
			'Haiku',
			'GPL',
			'b2',
			'blog',
			'website',

			// Other projects
			'Akismet',
			'BackPress',
			'BuddyPress',
			'bbPress',
			'VaultPress',
		);

		$filler = array(
			'consectetur',
			'adipisicing',
			'elit',
			'sed',
			'do',
			'eiusmod',
			'tempor',
			'incididunt',
			'ut',
			'labore',
			'et',
			'dolore',
			'magna',
			'aliqua',
			'ut',
			'enim',
			'ad',
			'minim',
			'veniam',
			'quis',
			'nostrud',
			'exercitation',
			'ullamco',
			'laboris',
			'nisi',
			'ut',
			'aliquip',
			'ex',
			'ea',
			'commodo',
			'consequat',
			'duis',
			'aute',
			'irure',
			'dolor',
			'in',
			'reprehenderit',
			'in',
			'voluptate',
			'velit',
			'esse',
			'cillum',
			'dolore',
			'eu',
			'fugiat',
			'nulla',
			'pariatur',
			'excepteur',
			'sint',
			'occaecat',
			'cupidatat',
			'non',
			'proident',
			'sunt',
			'in',
			'culpa',
			'qui',
			'officia',
			'deserunt',
			'mollit',
			'anim',
			'id',
			'est',
			'laborum');


		if ( true === $this->options['use_filler_words'] ) {
			$words = array_merge( $wpwords, $filler );
		} else {
			$words = $wpwords;
		}

		// Everyday I'm shufflin'
		shuffle( $words );

		return $words;

	}

	protected function generate_sentence() {
		// A sentence should be between 4 and 15 words.
		$sentence = '';
		$length = rand( 4, 15 );

		// Add a little more randomness to commas, about 2/3rds of the time
		$include_comma = $length >= 7 && rand( 0, 2 ) > 0;

		$words = $this->get_words();

		if ( count( $words ) > 0 ) {

			for ( $i = 0; $i < $length; $i++ ) {

				if ( $i > 0 ) {

					if ( $i >= 3 && $i != $length - 1 && $include_comma ) {

						if (rand(0,1) == 1) {
							$sentence = rtrim( $sentence ) . ', ';
							$include_comma = false;
						} else {
							$sentence .= ' ';
						}

					} else {

						$sentence .= ' ';

					}

				}

				$sentence .= $words[$i];
			}

			$sentence = rtrim($sentence) . '. ';
		}

		// Capitalize the first word, rest is lowercase
		$sentence =  ucfirst( strtolower( $sentence ) );
		// Making sure WordPress is properly capitalized
        $sentence = str_replace('Wordpress','WordPress',$sentence);
        $sentence = str_replace('wordpress','WordPress',$sentence);


		return $sentence;

	}

	protected function generate_paragraph()	{
		// A paragraph should be between 4 and 7 sentences.

		$paragraph = '';
		$length = rand( 4, 7 );

		for ($i = 0; $i < $length; $i++) {
			$paragraph .= $this->generate_sentence() . ' ';
		}

		return rtrim( $paragraph );

	}

	public function generate_lorem_ipsum() {

		$paragraphs = array();

		for ( $i = 0; $i < $this->options['paragraphs']; $i++ ) {
			$p = $this->generate_paragraph();

			if ( 0 === $i && $this->options['start_with_lorem'] ) {
				$p = 'WordPress ipsum dolor sit amet ' . strtolower( $p );
			}

			$paragraphs[]  = ucfirst( $p );
		}

		return $paragraphs;

	}

}