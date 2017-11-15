                # Para las cuentas de NIVEL 2
                        
                    $data = [
                                'code'      => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code,
                                'name'      => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_name,
                                'before'    => $before,
                                'actual'    => $actual,
                                'variation' => $variation
                            ];

                    if(array_search($data, $accountslvl1) === false) {$accountslvl1['account'] = $data;}

                    else {

                        $key = array_search($data, $accountslvl1);

                        $accountslvl1[$key]['before'] += $before;
                        $accountslvl1[$key]['actual'] += $actual;
                        $accountslvl1[$key]['variation'] = $accountslvl1[$key]['actual'] - 
                                                           $accountslvl1[$key]['before'];
                    }

                # Para las cuentas de NIVEL 1
                    
                    $data = [
                                'code'      => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code,
                                'name'      => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_name,
                                'before'    => $before,
                                'actual'    => $actual,
                                'variation' => $variation
                            ];

                    if(array_search($data, $accountslvl1) === false) {$accountslvl1['account'] = $data;}

                    else {

                        $key = array_search($data, $accountslvl1);

                        $accountslvl1[$key]['before'] += $before;
                        $accountslvl1[$key]['actual'] += $actual;
                        $accountslvl1[$key]['variation'] = $accountslvl1[$key]['actual'] - 
                                                           $accountslvl1[$key]['before'];
                    }



                
                # Para las cuentas de NIVEL 6

                    if( ! array_key_exists($account->account_code, $accountslvl6)) {

                        $accountslvl6[$account->account_code] = [

                            'code'      => $account->account_code,
                            'name'      => $account->account_name,
                            'before'    => $before,
                            'actual'    => $actual,
                            'variation' => $variation
                        ];
                    
                    } else {

                        return response()->json([

                            'status'    => false,
                            'message'   => '¡La cuenta '.$account->account_code.' está repetida! Por favor verifique he intente nuevamente.'
                        ]);
                    }

                # Para las cuentas de NIVEL 5
                        
                    if( ! array_key_exists($account->accountlvl5->account_code, $accountslvl5)) {

                        $accountslvl5[$account->accountlvl5->account_code] = [

                            'code'      => $account->accountlvl5->account_code,
                            'name'      => $account->accountlvl5->account_name,
                            'before'    => $before,
                            'actual'    => $actual,
                            'variation' => $variation
                        ];
                    
                    } else {

                        $accountslvl5[$account->accountlvl5->account_code]['before'] += $before;
                        $accountslvl5[$account->accountlvl5->account_code]['actual'] += $actual;
                        $accountslvl5[$account->accountlvl5->account_code]['variation'] = $accountslvl5[$account->accountlvl5->account_code]['actual'] - 
                                                                                          $accountslvl5[$account->accountlvl5->account_code]['before'];
                    }

                # Para las cuentas de NIVEL 4
                        
                    if( ! array_key_exists($account->accountlvl5->accountlvl4->account_code, $accountslvl4)) {

                        $accountslvl4[$account->accountlvl5->accountlvl4->account_code] = [

                            'code'      => $account->accountlvl5->accountlvl4->account_code,
                            'name'      => $account->accountlvl5->accountlvl4->account_name,
                            'before'    => $before,
                            'actual'    => $actual,
                            'variation' => $variation
                        ];
                    
                    } else {

                        $accountslvl4[$account->accountlvl5->accountlvl4->account_code]['before'] += $before;
                        $accountslvl4[$account->accountlvl5->accountlvl4->account_code]['actual'] += $actual;
                        $accountslvl4[$account->accountlvl5->accountlvl4->account_code]['variation'] = $accountslvl4[$account->accountlvl5->accountlvl4->account_code]['actual'] - 
                                                                                                       $accountslvl4[$account->accountlvl5->accountlvl4->account_code]['before'];
                    }

                # Para las cuentas de NIVEL 3
                        
                    if( ! array_key_exists($account->accountlvl5->accountlvl4->accountlvl3->account_code, $accountslvl3)) {

                        $accountslvl3[$account->accountlvl5->accountlvl4->accountlvl3->account_code] = [

                            'code'      => $account->accountlvl5->accountlvl4->accountlvl3->account_code,
                            'name'      => $account->accountlvl5->accountlvl4->accountlvl3->account_name,
                            'before'    => $before,
                            'actual'    => $actual,
                            'variation' => $variation
                        ];
                    
                    } else {

                        $accountslvl3[$account->accountlvl5->accountlvl4->accountlvl3->account_code]['before'] += $before;
                        $accountslvl3[$account->accountlvl5->accountlvl4->accountlvl3->account_code]['actual'] += $actual;
                        $accountslvl3[$account->accountlvl5->accountlvl4->accountlvl3->account_code]['variation'] = $accountslvl3[$account->accountlvl5->accountlvl4->accountlvl3->account_code]['actual'] - 
                                                                                                                    $accountslvl3[$account->accountlvl5->accountlvl4->accountlvl3->account_code]['before'];
                    }

                # Para las cuentas de NIVEL 2
                        
                    if( ! array_key_exists($account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code, $accountslvl2)) {

                        $accountslvl2[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code] = [

                            'code'      => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code,
                            'name'      => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_name,
                            'before'    => $before,
                            'actual'    => $actual,
                            'variation' => $variation
                        ];
                    
                    } else {

                        $accountslvl2[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code]['before'] += $before;
                        $accountslvl2[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code]['actual'] += $actual;
                        $accountslvl2[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code]['variation'] = $accountslvl2[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code]['actual'] - 
                                                                                                                                 $accountslvl2[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code]['before'];
                    }

                # Nivel 1

                    if( ! array_key_exists($account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code, $accountslvl1)) {

                        $accountslvl1[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code] = [

                            'code'      => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code,
                            'name'      => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_name,
                            'before'    => $before,
                            'actual'    => $actual,
                            'variation' => $variation
                        ];
                    
                    } else {

                        $accountslvl1[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code]['before'] += $before;
                        $accountslvl1[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code]['actual'] += $actual;
                        $accountslvl1[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code]['variation'] = $accountslvl1[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code]['actual'] - 
                                                                                                                                              $accountslvl1[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code]['before'];
                    }