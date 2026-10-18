// SPDX-License-Identifier: MIT
pragma solidity ^0.8.24;

import {ERC20} from "@openzeppelin/contracts/token/ERC20/ERC20.sol";

interface IERC20Transfer {
    function transfer(address to, uint256 value) external returns (bool);
    function transferFrom(address from, address to, uint256 value) external returns (bool);
}

contract MockPancakePair is ERC20 {
    address public token0;
    address public token1;

    constructor(address _token0, address _token1) ERC20("Pancake LPs", "Cake-LP") {
        token0 = _token0;
        token1 = _token1;
    }

    receive() external payable {}
    fallback() external payable {}
}

contract MockPancakeFactory {
    mapping(address => mapping(address => address)) public getPair;

    function createPair(address tokenA, address tokenB) external returns (address pair) {
        MockPancakePair newPair = new MockPancakePair(tokenA, tokenB);
        pair = address(newPair);
        getPair[tokenA][tokenB] = pair;
        getPair[tokenB][tokenA] = pair;
    }
}

contract MockPancakeRouter {
    address public factory;

    constructor(address _factory) {
        factory = _factory;
    }

    receive() external payable {}
    fallback() external payable {}

    function swapExactTokensForTokensSupportingFeeOnTransferTokens(
        uint256 amountIn,
        uint256 amountOutMin,
        address[] calldata path,
        address to,
        uint256 /* deadline */
    ) external {
        // Pull token from caller
        IERC20Transfer(path[0]).transferFrom(msg.sender, address(this), amountIn);
        
        // Transfer simulated output to recipient (if router holds it)
        // If path[path.length - 1] is holding tokens, transfer
        uint256 outAmount = amountOutMin > 0 ? amountOutMin : amountIn;
        try IERC20Transfer(path[path.length - 1]).transfer(to, outAmount) {} catch {}
    }

    function getAmountsOut(uint256 amountIn, address[] calldata path)
        external
        pure
        returns (uint256[] memory amounts)
    {
        amounts = new uint256[](path.length);
        amounts[0] = amountIn;
        amounts[path.length - 1] = amountIn;
    }
}
