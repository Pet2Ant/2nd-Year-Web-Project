<?php

class PostInfoView extends PostInfo
{
    private function upvoteCreator($isClicked)
    {
        if ($isClicked == true) {
            return '<button type="submit" name="upvote" class="text-xs">
                    <svg class="w-5 fill-current  text-[#ff4057] "xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M7 10v8h6v-8h5l-8-8-8 8h5z"></path>
                    </svg>
                </button>';
        }
        return '<button type="submit" name="upvote" class="text-xs">
        <svg class="w-5 fill-current text-gray-500 transition duration-500 hover:text-[#ff4057]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M7 10v8h6v-8h5l-8-8-8 8h5z"></path>
        </svg>
        </button>';
    }

    private function downvoteCreator($isClicked)
    {
        if ($isClicked == true) {
            return '<button type="submit" name="downvote" class="text-xs">
            <svg class="w-5 fill-current text-blue-500 "xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M7 10V2h6v8h5l-8 8-8-8h5z"></path>
            </svg>
            </button>';
        }
        return '<button type="submit" name="downvote" class="text-xs">
        <svg class="w-5 fill-current text-gray-500 transition duration-500 hover:text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M7 10V2h6v8h5l-8 8-8-8h5z"></path>
        </svg>
        </button>';
    }

    public function fetchTitle($id)
    {
        $postInfo = $this->getUserPosts($id);
        echo $postInfo[0]["post_title"];
    }

    public function fetchContent($id)
    {
        $postInfo = $this->getUserPosts($id);
        echo $postInfo[0]["post_content"];
    }

    public function createPostFe($id)
    {

        $count = 0;
        $maxcount = $this->fetchAllPosts(-1) - 1; //FIXME convert to db this entire transaction
        $row = $this->postRows();

        while ($count < $maxcount) {

            //Do not show non user posts
            if ($row[$count]["users_id"] != $id) {
                $count++;
                continue;
            }

            $votecap = $this->getVotecap($row[$count]["post_id"], $id);

            if ($votecap == false) {
                $downvote = $this->downvoteCreator(false);
                $upvote = $this->upvoteCreator(false);
            } else {
                if ($votecap["votecap"] == 1) {
                    $upvote = $this->upvoteCreator(true);
                    $downvote = $this->downvoteCreator(false);
                } elseif ($votecap["votecap"] == -1) {
                    $downvote = $this->downvoteCreator(true);
                    $upvote = $this->upvoteCreator(false);
                } else {
                    $downvote = $this->downvoteCreator(false);
                    $upvote = $this->upvoteCreator(false);
                }
            }

            echo '<div id="" class="py-2 mb-4">
                <div class="flex border border-[#343536] bg-[#272729] transition duration-500 ease-in-out hover:border-red-500 rounded cursor-pointer">
                    <div class="w-5 mx-4 flex flex-col text-center pt-2">
                        <!-- Upvote -->
                        <form action="../karma.php" method="post">
                        
                        <input type="text" name="post_upvote" value=' . $row[$count]["post_id"] . '  hidden>' . $upvote . '
                    
                        
                        <!-- Vote count -->
                        <span class="text-xs font-semibold my-1 text-gray-500"> ' . $row[$count]["post_karma"] . '</span>
                        <!-- Downvote -->
                        
                        <input type="text" name="post_downvote" value=' . $row[$count]["post_id"] . '  hidden >' . $downvote . '
                        
                        </form>
                    </div>
                    <!-- Post Information -->
                    <div class="w-11/12 pt-2" onclick="javascript:window.location.href=\'../public/page.php?p=' . $row[$count]["post_id"] . '\'">
                    
                        <div class="flex items-center text-xs mb-2">
                            <span class="text-gray-500">Posted by</span>
                            <a href="../public/Profile.php' . $row[$count]["username"] . '" class="text-gray-500 mx-1 no-underline hover:underline">ku/' . $row[$count]["username"] . '</a>
                            <span class="text-gray-500">' .$row[$count]["date"]. '</span>
                        </div>
                        <!-- Post Title -->
                        <div>
                            <h2 class="text-lg font-bold mb-1 text-gray-400 break-all">
                            ' . $row[$count]["post_title"] . '
                            </h2>
                        </div>
                        <!-- Post Description -->
                        <p class="text-gray-500 break-all">
                            ' . $row[$count]["post_content"] . '

                        </p>
                        <!-- Comments -->
                        <div class="inline-flex items-center my-1">
                            <div class="flex transition duration-500 hover:bg-gray-700 p-1 rounded-lg">
                                <svg class="w-4 fill-current text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M10 15l-4 4v-4H2a2 2 0 0 1-2-2V3c0-1.1.9-2 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-8zM5 7v2h2V7H5zm4 0v2h2V7H9zm4 0v2h2V7h-2z"></path>
                                </svg>
                                <span class="ml-2 text-xs font-semibold text-gray-500">3k Comments</span>
                            </div>
                            <!-- Share -->
                            <div class="flex transition duration-500 hover:bg-gray-700 p-1 ml-2 rounded-lg">
                                <svg class="w-4 fill-current text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M5.08 12.16A2.99 2.99 0 0 1 0 10a3 3 0 0 1 5.08-2.16l8.94-4.47a3 3 0 1 1 .9 1.79L5.98 9.63a3.03 3.03 0 0 1 0 .74l8.94 4.47A2.99 2.99 0 0 1 20 17a3 3 0 1 1-5.98-.37l-8.94-4.47z"></path>
                                </svg>
                                <span class="ml-2 text-xs font-semibold text-gray-500">Share</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

            $count = $count + 1;

        }
    }
    public function upvoteCount($post_id, $id)
    {
        

        $votecap = $this->getVotecap($post_id, $id);
        if ($votecap == false) {
            $upvotes = $this->upvotes($post_id);
            $upvotesNew = $upvotes[0]["post_upvote"] + 1;
            $this->upvote($upvotesNew, $post_id);
            $this->createVotecap($id, $post_id, 1);
            return;
        }
        if ($votecap["votecap"] == 1) {
            $this->deleteVotecap($id, $post_id);
            $upvotes = $this->upvotes($post_id);
            $upvotesNew = $upvotes[0]["post_upvote"] - 1;
            $this->upvote($upvotesNew, $post_id);
            return;
        }


        if ($votecap["votecap"] == -1) {
            $downvotes = $this->downvotes($post_id);
            $downvotesNew = $downvotes[0]["post_downvote"] - 1;
            $this->downvote($downvotesNew, $post_id);

            $upvotes = $this->upvotes($post_id);
            $upvotesNew = $upvotes[0]["post_upvote"] + 1;
            $this->upvote($upvotesNew, $post_id);

            $this->updateVotecapPos($post_id, $id);

        }
    }
    public function downvoteCount($post_id, $id)
    {
        

        $votecap = $this->getVotecap($post_id, $id);
        if ($votecap == false) {
            $downvotes = $this->downvotes($post_id);
            $downvotesNew = $downvotes[0]["post_downvote"] + 1;
            $this->downvote($downvotesNew, $post_id);
            $this->createVotecap($id, $post_id, -1);
            return;
        }
        if ($votecap["votecap"] == -1) {
            $this->deleteVotecap($id, $post_id);
            $downvotes = $this->downvotes($post_id);
            $downvotesNew = $downvotes[0]["post_downvote"] - 1;
            $this->downvote($downvotesNew, $post_id);
            return;
        }
        if ($votecap["votecap"] == 1) {
            $upvotes = $this->upvotes($post_id);
            $upvotesNew = $upvotes[0]["post_upvote"] - 1;

            $this->upvote($upvotesNew, $post_id);

            $downvotes = $this->downvotes($post_id);
            $downvotesNew = $downvotes[0]["post_downvote"] + 1;
            $this->downvote($downvotesNew, $post_id);

            $this->updateVotecapNeg($post_id, $id);
        }
    }
    public function updateKarma($post_id)
    {
        $upvotes = $this->upvotes($post_id);

        $downvotes = $this->downvotes($post_id);
        if ($downvotes[0]["post_downvote"] >= 0) {
            $karma = $upvotes[0]["post_upvote"] - $downvotes[0]["post_downvote"];
        }
        else
        {
            $karma = $upvotes[0]["post_upvote"] + $downvotes[0]["post_downvote"];
        }



        $this->Karma($karma,$post_id);
    }
    public function createComment($users_id,$post_id,$text)
    {
        $this->createCommmentDb($users_id, $post_id, $text);
    }   
    public function fetchComment($post_id)
    
    {
        $postComments=$this->fetchCommentDb($post_id);
        $commentCount = count($postComments);
        for ($i = 0; $i < $commentCount; $i++) {
            echo '
            <div class="flex border border-[#343536] bg-[#272729] rounded p-3">
            <div class="w-11/12 pt-2">
            <!-- Comment Information -->
            <div class="flex items-center text-sm mb-2">
                <img class="w-8 h-8 rounded-full mr-2" src="https://placeimg.com/192/192/people" alt="Avatar of User">
                <span class="text-gray-500">Commented by</span>
                <a href="#" class="text-gray-500 mx-1 no-underline hover:underline">ku/'. $postComments[$i]["username"] .'•</a>
                <span class="text-gray-500">'. $postComments[$i]["date"] .'</span>
            </div>
            <!-- Comment Text -->
            <p class="text-gray-400 text-md">
            '. $postComments[$i]["text"] .'
            </p>
            <!-- Comment Actions -->
            <div class="w-5 mx-4 flex flex-row text-center pt-2 space-x-4">
                <!-- Upvote -->
                <button class="text-gray-500 transition duration-500 hover:text-red-500 duration-500 hover:bg-gray-700 p-0.5 rounded-lg flex flex-row">
                    <input type="text" hidden disabled>
                    <svg class="w-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M7 10v8h6v-8h5l-8-8-8 8h5z"></path>
                    </svg>
                    <span class="ml-2 text-xs font-semibold text-gray-500 py-0.5 "></span>
                    <span class="ml-2 text-xs font-semibold text-gray-500 py-0.5 ">Upvote</span>
                </button>
                <!-- Vote count -->
                <span class="text-xs font-semibold my-1 m-2 text-gray-400">0</span>
                <!-- Downvote -->
                <button class="text-gray-500 transition duration-500 hover:text-blue-500 duration-500 hover:bg-gray-700 p-0.5 rounded-lg flex flex-row">
                    <input type="text" hidden disabled>
                    <svg class="w-5 fill-current " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M7 10V2h6v8h5l-8 8-8-8h5z"></path>
                    </svg>
                    <span class="ml-2 text-xs font-semibold text-gray-500 py-0.5 "></span>
                    <span class="ml-2 text-xs font-semibold text-gray-500 py-0.5 ">Downvote</span>
                </button>
            </div>
        </div>
        </div>
            ';
        }
    }
    public function fetchUserComment($id)
    {
        $userComments = $this->fetchUserCommentDb($id);
        $commentCount = count($userComments);
        for ($i = 0; $i < $commentCount; $i++) {
            echo '  
                        <div class="flex border border-[#343536] bg-[#272729] rounded p-3 ">
                            <!-- Comment Body -->
                            <div class="w-11/12 pt-2">
                                <!-- Comment Information -->
                                <div class="flex items-center text-sm mb-2">
                                    <img class="w-8 h-8 rounded-full mr-2" src="https://placeimg.com/192/192/people" alt="Avatar of User">
                                    <span class="text-gray-500">Commented by</span>
                                    <a href="#" class="text-gray-500 mx-1 no-underline hover:underline">ku/'.$userComments[$i]["username"].' •</a>
                                    <span class="text-gray-500">'.$userComments[$i]["date"].'</span>
                                </div>
                                <!-- Comment Text -->
                                <p class="text-gray-400 text-md">
                                '.$userComments[$i]["text"].'
                                </p>
                                <!-- Comment Actions -->
                                <div class="w-5 mx-4 flex flex-row text-center pt-2 space-x-4">
                                    <!-- Upvote -->
                                    <button class="text-gray-500 transition duration-500 hover:text-red-500 duration-500 hover:bg-gray-700 p-0.5 rounded-lg flex flex-row">
                                        <input type="text" hidden disabled>
                                        <svg class="w-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path d="M7 10v8h6v-8h5l-8-8-8 8h5z"></path>
                                        </svg>
                                        <span class="ml-2 text-xs font-semibold text-gray-500 py-0.5 "></span>
                                        <span class="ml-2 text-xs font-semibold text-gray-500 py-0.5 ">Upvote</span>
                                    </button>
                                    <!-- Vote count -->
                                    <span class="text-xs font-semibold my-1 m-2 text-gray-400">0</span>
                                    <!-- Downvote -->
                                    <button class="text-gray-500 transition duration-500 hover:text-blue-500 duration-500 hover:bg-gray-700 p-0.5 rounded-lg flex flex-row">
                                        <input type="text" hidden disabled>
                                        <svg class="w-5 fill-current " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path d="M7 10V2h6v8h5l-8 8-8-8h5z"></path>
                                        </svg>
                                        <span class="ml-2 text-xs font-semibold text-gray-500 py-0.5 "></span>
                                        <span class="ml-2 text-xs font-semibold text-gray-500 py-0.5 ">Downvote</span>
                                    </button>
                                </div>
                            
                        </div>
                    </div> ';
             
        }
    }
}
          

?>
