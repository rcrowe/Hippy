<?php

//Include neccessery Hippy files
require_once 'Hippy/Base.php';
require_once 'Hippy/Room.php';

/**
 * Hippy - PHP client for HipChat. Designed for incidental notifications from an application.
 *
 * Example
 * -------
 *
 * <code>
 *
 *     $settings = array(
 *         'token'  => 'abc123',
 *         'room'   => 'General',
 *         'from'   => 'rcrowe',
 *         'notify' => true
 *     );
 *
 *     Hippy::settings($settings);
 *
 *     Hippy::speak('Did the build succedded');
 *     Hippy::speak('Yes, build succedded');
 *
 * </code>
 *
 * @author Rob "VivaLaCrowe" Crowe <nobby.crowe@gmail.com>
 * @license LGPL
 *
 */
class Hippy extends Hippy_Base
{

    /**
     * Sends a message to a HipChat room. Short hand function if only sending one message
     *
     * @param string       $msg    Message to send to the room. Text is UTF8 encoded.
     * @param array|string $config Either an array of settings or API token.
     *
     * @internal Uses Hippy_Room::speak() to send message. This is just a shortcut
     *
     * @throws HippyException
     */
    public static function speak($msg, $config = NULL)
    {
        //Set settings
        parent::settings($config);
        
        //Get an instance of Hippy_Room and send message
        $room = new Hippy_Room();
        $room->speak($msg);
    }
    
    /**
     * Returns an instance of a HipChat room so you can send multiple messages
     *
     * <code>
     *
     *     Hippy::settings($settings);
     *     
     *     $room = Hippy::room()->speak('Testing Hippy');
     *     $room->speak('Build succedded on branch master');
     *
     * </code>
     *
     * @param string|int $room Room name or ID to send messages to
     *
     * @return Hippy_Room
     */
    public static function room($room = NULL)
    {
        //Set the room to send message to if compatible
        if(!empty($room) && (is_numeric($room) || is_string($room)))
        {
            parent::settings(array('room' => $room));
        }
        
        //Instance of Hippy_Room
        return new Hippy_Room();
    }
}

?>